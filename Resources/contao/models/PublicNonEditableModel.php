<?php


namespace con4gis\MapContentBundle\Resources\contao\models;


use con4gis\MapContentBundle\Resources\contao\modules\PublicNonEditableModule;
use con4gis\ProjectsBundle\Classes\Common\C4GBrickCommon;
use Contao\StringUtil;

class PublicNonEditableModel
{
    public static function find() {

        $db = \Database::getInstance();
        $stmtTypes = $db->prepare("SELECT * FROM tl_c4g_mapcontent_type");
        $resultTypes = $stmtTypes->execute()->fetchAllAssoc();
        $types = [];
        foreach ($resultTypes as $rt) {
            $types[$rt['id']] = $rt['name'];
        }

        if (PublicNonEditableModule::$type) {
            $stmtElements = $db->prepare("SELECT * FROM tl_c4g_mapcontent_element WHERE name != '' AND type = ? ORDER BY name ASC");
            $resultElements = $stmtElements->execute(PublicNonEditableModule::$type)->fetchAllAssoc();
        } else {
            $stmtElements = $db->prepare("SELECT * FROM tl_c4g_mapcontent_element WHERE name != '' ORDER BY name ASC");
            $resultElements = $stmtElements->execute()->fetchAllAssoc();
        }

        foreach ($resultElements as $key => $re) {

            if (intval($re['parentElement']) > 0) {
                $toMerge = [
                    $re
                ];
                while (intval($toMerge[0]['parentElement']) > 0) {
                    array_unshift($toMerge, MapcontentElementModel::findByPk([$toMerge[0]['parentElement']])->row());
                }

                $merge = [];
                foreach ($toMerge as $merging) {
                    foreach ($merging as $k => $v) {
                        switch ($k) {
                            case 'businessHours':
                            case 'linkWizard':
                                $array = StringUtil::deserialize($v);
                                foreach ($array as $entry) {
                                    foreach ($entry as $item) {
                                        if ($item !== '') {
                                            $merge[$k] = $v;
                                            break 2;
                                        }
                                    }
                                }
                                break;
                            default:
                                $merge[$k] = $v ? $v : $merge[$k];
                                if ($merge[$k] === null) {
                                    $merge[$k] = '';
                                }
                        }
                    }
                }
                if (!empty($merge)) {
                    $re = $merge;
                    $resultElements[$key] = $merge;
                }
            }

            $resultElements[$key]['type'] = $types[$re['type']];
            $resultElements[$key]['addressName'] = $re['addressName'];
            if ($re['addressStreetNumber'] !== '0') {
                $resultElements[$key]['addressStreet'] = $re['addressStreet'] . ' ' . $re['addressStreetNumber'];
            } else {
                $resultElements[$key]['addressStreet'] = $re['addressStreet'];
            }
            $resultElements[$key]['addressCity'] = $re['addressZip'] . ' ' . $re['addressCity'];

            $timeString = [];
            $businessTimes = \StringUtil::deserialize($re['businessHours']);
            $resultElements[$key]['businessHours'] = '';
            foreach ($businessTimes as $k => $time) {
                $timeString[$k] = '';
                if ($time['dayFrom'] !== '' && $time['timeFrom'] !== '' && $time['timeTo'] !== '') {
                    $timeString[$k] .= $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_reference'][$time['dayFrom']];
                    if ($time['dayTo'] !== $time['dayFrom'] && $time['dayTo'] !== '') {
                        if (abs(intval($time['dayTo']) - intval($time['dayFrom'])) > 1) {
                            $join = $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_join']['to'];
                        } else {
                            $join = $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_join']['and'];
                        }

                        $timeString[$k] .= " $join " . $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_reference'][$time['dayTo']];
                    }
                    $timeString[$k] .= ": " . date('H:i', $time['timeFrom']) .
                        $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['timeCaption'] .
                        " - " . date('H:i', $time['timeTo']) .
                        $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['timeCaption'];
                }
            }

            $bH = [];
            $entries = [];
            foreach ($timeString as $string) {
                $explode = explode(': ', $string);
                $k = $explode[0];
                if (isset($bH[$k]) === true) {
                    $bH[$k] .= ', '.$explode[1];
                } else {
                    $bH[$k] = $explode[1];
                }
            }
            foreach ($bH as $k => $v) {
                if (!empty($v)) {
                    $entries[] = $k.': '.$v;
                } else {
                    $entries[] = $k;
                }
            }

            if ($re['businessHoursAdditionalInfo'] !== '') {
                $entries[] = $re['businessHoursAdditionalInfo'];
            }

            foreach ($entries as $entry) {
                $resultElements[$key]['businessHours'] .= '<li class="c4g_brick_list_column c4g_brick_list_row_column businessHours">'.$entry.'</li>';
            }

            $models = MapcontentCustomFieldModel::findAll();
            if ($models !== null) {
                foreach ($models as $model) {
                    if ($model->type === 'multicheckbox') {
                        $resultElements[$key][$model->alias] = StringUtil::deserialize($resultElements[$key][$model->alias]);
                        if (is_array($resultElements[$key][$model->alias])) {
                            $resultElements[$key][$model->alias] = $model->name.": ". implode(', ', $resultElements[$key][$model->alias]);
                        }
                    }
                }
            }
        }

        return C4GBrickCommon::arrayToObject($resultElements);
    }

    public static function findByPk($pk) {
        $model = MapcontentElementModel::findByPk($pk);
        $array = $model->row();

        if (intval($array['parentElement']) > 0) {
            $toMerge = [
                $array
            ];
            while (intval($toMerge[0]['parentElement']) > 0) {
                array_unshift($toMerge, MapcontentElementModel::findByPk([$toMerge[0]['parentElement']])->row());
            }

            $merge = [];
            foreach ($toMerge as $merging) {
                foreach ($merging as $k => $v) {
                    switch ($k) {
                        case 'businessHours':
                        case 'linkWizard':
                            $arr = StringUtil::deserialize($v);
                            foreach ($arr as $entry) {
                                foreach ($entry as $item) {
                                    if ($item !== '') {
                                        $merge[$k] = $v;
                                        break 2;
                                    }
                                }
                            }
                            break;
                        default:
                            $merge[$k] = $v ? $v : $merge[$k];
                            if ($merge[$k] === null) {
                                $merge[$k] = '';
                            }
                    }
                }
            }
            if (!empty($merge)) {
                $array = $merge;
            }
        }

        $address = [];
        if ($array['addressStreet'] !== '' && $array['addressStreetNumber'] !== '0') {
            $array['addressStreet'] = $array['addressStreet']. ' ' . $array['addressStreetNumber'];
        }
        if ($array['addressZip'] !== '' && $array['addressCity'] !== '') {
            $array['addressCity'] = $array['addressZip'] . ' ' . $array['addressCity'];
        }
        $array['address'] = implode(', ',  $address);

        $timeString = [];
        $businessTimes = \StringUtil::deserialize($array['businessHours']);
        $array['businessHours'] = '';
        foreach ($businessTimes as $k => $time) {
            $timeString[$k] = '';
            if ($time['dayFrom'] !== '' && $time['timeFrom'] !== '' && $time['timeTo'] !== '') {
                $timeString[$k] .= $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_reference'][$time['dayFrom']];
                if ($time['dayTo'] !== $time['dayFrom'] && $time['dayTo'] !== '') {
                    if (abs(intval($time['dayTo']) - intval($time['dayFrom'])) > 1) {
                        $join = $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_join']['to'];
                    } else {
                        $join = $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_join']['and'];
                    }

                    $timeString[$k] .= " $join " . $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_reference'][$time['dayTo']];
                }
                $timeString[$k] .= ": " . date('H:i', $time['timeFrom']) .
                    $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['timeCaption'] .
                    " - " . date('H:i', $time['timeTo']) .
                    $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['timeCaption'];
            }
        }

        $bH = [];
        $entries = [];
        foreach ($timeString as $string) {
            $explode = explode(': ', $string);
            $k = $explode[0];
            if (isset($bH[$k]) === true) {
                $bH[$k] .= ', '.$explode[1];
            } else {
                $bH[$k] = $explode[1];
            }
        }
        foreach ($bH as $k => $v) {
            if (!empty($v)) {
                $entries[] = $k.': '.$v;
            } else {
                $entries[] = $k;
            }
        }

        if ($array['businessHoursAdditionalInfo'] !== '') {
            $entries[] = $array['businessHoursAdditionalInfo'];
        }

        foreach ($entries as $entry) {
            $array['businessHours'] .= '<li class="c4g_brick_list_column c4g_brick_list_row_column businessHours">'.$entry.'</li>';
        }

        return C4GBrickCommon::arrayToObject($array);
    }
}