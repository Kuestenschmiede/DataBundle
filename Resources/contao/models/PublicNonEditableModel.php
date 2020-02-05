<?php


namespace con4gis\DataBundle\Resources\contao\models;

use con4gis\CoreBundle\Classes\Helper\ArrayHelper;
use con4gis\DataBundle\Resources\contao\modules\PublicNonEditableModule;
use con4gis\ProjectsBundle\Classes\Common\C4GBrickCommon;
use Contao\Database;
use Contao\StringUtil;

class PublicNonEditableModel
{
    public static function find() {

        $db = \Database::getInstance();
        $stmtTypes = $db->prepare("SELECT * FROM tl_c4g_data_type");
        $resultTypes = $stmtTypes->execute()->fetchAllAssoc();
        $types = [];
        foreach ($resultTypes as $rt) {
            $types[$rt['id']] = $rt['name'];
        }

        if (PublicNonEditableModule::$type) {
            $stmtElements = $db->prepare("SELECT * FROM tl_c4g_data_element WHERE name != '' AND type = ? ORDER BY name ASC");
            $resultElements = $stmtElements->execute(PublicNonEditableModule::$type)->fetchAllAssoc();
        } else {
            if (PublicNonEditableModule::$directory) {
                $directoryModel = DataDirectoryModel::findByPk(PublicNonEditableModule::$directory);
                if ($directoryModel !== null) {
                    $types = StringUtil::deserialize($directoryModel->types);
                    $resultElements = [];
                    foreach ($types as $type) {
                        $stmtElements = $db->prepare("SELECT * FROM tl_c4g_data_element WHERE name != '' AND type = ? ORDER BY name ASC");
                        $resultElements = array_merge($resultElements, $stmtElements->execute($type)->fetchAllAssoc());
                    }
                } else {
                    $stmtElements = $db->prepare("SELECT * FROM tl_c4g_data_element WHERE name != '' ORDER BY name ASC");
                    $resultElements = $stmtElements->execute()->fetchAllAssoc();
                }
            } else {
                $stmtElements = $db->prepare("SELECT * FROM tl_c4g_data_element WHERE name != '' ORDER BY name ASC");
                $resultElements = $stmtElements->execute()->fetchAllAssoc();
            }
        }

        foreach ($resultElements as $key => $re) {

            if (intval($re['parentElement']) > 0) {
                $toMerge = [
                    $re
                ];
                while (intval($toMerge[0]['parentElement']) > 0) {
                    array_unshift($toMerge, DataElementModel::findByPk([$toMerge[0]['parentElement']])->row());
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

            $typeModel = DataTypeModel::findByPk($resultElements[$key]['type']);
            $resultElements[$key]['itemType'] = strval($typeModel->itemType);

            $resultElements[$key]['type'] = $types[$re['type']];

            $address = [];
            $address[] = $re['addressName'];
            if ($resultElements[$key]['itemType'] !== '') {
                if ($re['addressStreetNumber'] !== '0' && $re['addressStreet'] !== '') {
                    $address[] = '<span itemprop="streetAddress">' . $re['addressStreet'] . ' ' . $re['addressStreetNumber'] . '</span>';
                } elseif ($re['addressStreet'] !== '') {
                    $address[] = '<span itemprop="streetAddress">' . $re['addressStreet'] . '</span>';
                }

                if ($re['addressZip'] && $re['addressCity']) {
                    $address[] = '<span itemprop="postalCode">' . $re['addressZip'] .
                        '</span> <span itemprop="addressLocality">' . $re['addressCity'] . '</span>';
                }


                if ($re['addressState'] !== '' && $re['addressCountry'] !== '') {
                    $address[] = '<span itemprop="addressRegion">' . $re['addressState'] .
                        '</span>, <span itemprop="addressCountry">' . $re['addressCountry'] . '</span>';
                } elseif ($re['addressCountry'] !== '') {
                    $address[] = '<span itemprop="addressCountry">' . $re['addressCountry'] . '</span>';
                }
            } else {
                if ($re['addressStreetNumber'] !== '0' && $re['addressStreet'] !== '') {
                    $address[] = '<span>' . $re['addressStreet'] . ' ' . $re['addressStreetNumber'] . '</span>';
                } elseif ($re['addressStreet'] !== '') {
                    $address[] = '<span>' . $re['addressStreet'] . '</span>';
                }

                if ($re['addressZip'] && $re['addressCity']) {
                    $address[] = '<span>' . $re['addressZip'] .
                        '</span> <span>' . $re['addressCity'] . '</span>';
                }


                if ($re['addressState'] !== '' && $re['addressCountry'] !== '') {
                    $address[] = '<span>' . $re['addressState'] .
                        '</span>, <span>' . $re['addressCountry'] . '</span>';
                } elseif ($re['addressCountry'] !== '') {
                    $address[] = '<span>' . $re['addressCountry'] . '</span>';
                }
            }

            $resultElements[$key]['address'] = '';
            foreach ($address as $part) {
                if ($part !== '') {
                    $resultElements[$key]['address'] .= "<span>$part</span>";
                }
            }

            $timeString = [];
            $businessTimes = \StringUtil::deserialize($re['businessHours']);
            $resultElements[$key]['businessHours'] = '';
            foreach ($businessTimes as $k => $time) {
                $timeString[$k] = '';
                if ($time['dayFrom'] !== '' && $time['timeFrom'] !== '' && $time['timeTo'] !== '') {
                    $timeString[$k] .= $GLOBALS['TL_LANG']['tl_c4g_data_element']['day_reference'][$time['dayFrom']];
                    if ($time['dayTo'] !== $time['dayFrom'] && $time['dayTo'] !== '') {
                        if (abs(intval($time['dayTo']) - intval($time['dayFrom'])) > 1) {
                            $join = $GLOBALS['TL_LANG']['tl_c4g_data_element']['day_join']['to'];
                        } else {
                            $join = $GLOBALS['TL_LANG']['tl_c4g_data_element']['day_join']['and'];
                        }

                        $timeString[$k] .= " $join " . $GLOBALS['TL_LANG']['tl_c4g_data_element']['day_reference'][$time['dayTo']];
                    }
                    $timeString[$k] .= ": " . date('H:i', $time['timeFrom']) .
                        $GLOBALS['TL_LANG']['tl_c4g_data_element']['timeCaption'] .
                        " - " . date('H:i', $time['timeTo']) .
                        $GLOBALS['TL_LANG']['tl_c4g_data_element']['timeCaption'];
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

            $resultElements[$key]['searchInfo'] = '';
            $models = DataCustomFieldModel::findAll();
            if ($models !== null) {
                foreach ($models as $model) {
                    if ($model->type === 'multicheckbox') {
                        if ($model->frontendList === '1') {
                            $options = StringUtil::deserialize($model->options);
                            $resultElements[$key][$model->alias] = StringUtil::deserialize($resultElements[$key][$model->alias]);
                            if (is_array($resultElements[$key][$model->alias])) {
                                $displayValues = [];
                                foreach ($resultElements[$key][$model->alias] as $value) {
                                    foreach ($options as $option) {
                                        if ($option['key'] === $value) {
                                            $displayValues[] = $option['value'];
                                        }
                                    }
                                }
                                $resultElements[$key][$model->alias] = $model->name . ": " . implode(', ', $displayValues);
                            }
                        } else {
                            $resultElements[$key][$model->alias] = StringUtil::deserialize($resultElements[$key][$model->alias]);
                            if (is_array($resultElements[$key][$model->alias])) {
                                $resultElements[$key]['searchInfo'] .= implode(', ', $resultElements[$key][$model->alias]) . " ";
                            }
                        }
                    } elseif ($model->type === 'select') {
                        if ($resultElements[$key][$model->alias]) {
                            $options = StringUtil::deserialize($model->options);
                            if ($options !== null) {
                                foreach ($options as $option) {
                                    if ($option['key'] === $resultElements[$key][$model->alias]) {
                                        $resultElements[$key][$model->alias] = $option['value'];
                                    }
                                }
                            }
                        }
                    } elseif ($model->type === 'foreignKey') {
                        if (intval($resultElements[$key][$model->alias]) !== 0) {
                            $stmt = Database::getInstance()->prepare(
                                "SELECT ".
                                $model->foreignField.
                                " FROM ".
                                $model->foreignTable.
                                " WHERE id = ?"
                            );
                            try {
                                $resultElements[$key][$model->alias] = $stmt->execute($resultElements[$key][$model->alias])
                                    ->fetchAllAssoc()[0][$model->foreignField];
                            } catch (\Throwable $throwable) {
                                $resultElements[$key][$model->alias] = '';
                            }
                        } else {
                            $resultElements[$key][$model->alias] = '';
                        }
                    }
                }
            }

            $resultElements[$key]['searchInfo'] .= $resultElements[$key]['type'];
        }

        return ArrayHelper::arrayToObject($resultElements);
    }

    public static function findByPk($pk) {
        $model = DataElementModel::findByPk($pk);
        $array = $model->row();

        if (intval($array['parentElement']) > 0) {
            $toMerge = [
                $array
            ];
            while (intval($toMerge[0]['parentElement']) > 0) {
                array_unshift($toMerge, DataElementModel::findByPk([$toMerge[0]['parentElement']])->row());
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

        if ($array['addressStreet'] !== '' && $array['addressStreetNumber'] !== '0') {
            $array['addressStreet'] = $array['addressStreet']. ' ' . $array['addressStreetNumber'];
        }
        if ($array['addressZip'] !== '' && $array['addressCity'] !== '') {
            $array['addressCity'] = $array['addressZip'] . ' ' . $array['addressCity'];
        }
        if ($array['addressState'] !== '' && $array['addressCountry'] !== '') {
            $array['addressCountry'] = $array['addressState'] . ', ' . $array['addressCountry'];
            $array['addressState'] = '';
        }

        $typeModel = DataTypeModel::findByPk($array['type']);
        $array['type'] = $typeModel->name;

        $timeString = [];
        $businessTimes = \StringUtil::deserialize($array['businessHours']);
        $array['businessHours'] = '';
        foreach ($businessTimes as $k => $time) {
            $timeString[$k] = '';
            if ($time['dayFrom'] !== '' && $time['timeFrom'] !== '' && $time['timeTo'] !== '') {
                $timeString[$k] .= $GLOBALS['TL_LANG']['tl_c4g_data_element']['day_reference'][$time['dayFrom']];
                if ($time['dayTo'] !== $time['dayFrom'] && $time['dayTo'] !== '') {
                    if (abs(intval($time['dayTo']) - intval($time['dayFrom'])) > 1) {
                        $join = $GLOBALS['TL_LANG']['tl_c4g_data_element']['day_join']['to'];
                    } else {
                        $join = $GLOBALS['TL_LANG']['tl_c4g_data_element']['day_join']['and'];
                    }

                    $timeString[$k] .= " $join " . $GLOBALS['TL_LANG']['tl_c4g_data_element']['day_reference'][$time['dayTo']];
                }
                $timeString[$k] .= ": " . date('H:i', $time['timeFrom']) .
                    $GLOBALS['TL_LANG']['tl_c4g_data_element']['timeCaption'] .
                    " - " . date('H:i', $time['timeTo']) .
                    $GLOBALS['TL_LANG']['tl_c4g_data_element']['timeCaption'];
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
            if (trim($entry) !== '') {
                $array['businessHours'] .= '<li class="c4g_brick_list_column c4g_brick_list_row_column businessHours">'.$entry.'</li>';
            }
        }

        $customFields = DataCustomFieldModel::findAll();
        if ($customFields !== null) {
            foreach ($customFields as $customField) {
                if ($customField->type === 'select') {
                    if ($array[$customField->alias]) {
                        $options = StringUtil::deserialize($customField->options);
                        if ($options !== null) {
                            foreach ($options as $option) {
                                if ($option['key'] === $array[$customField->alias]) {
                                    $array[$customField->alias] = $option['value'];
                                }
                            }
                        }
                    }
                } elseif ($customField->type === 'multicheckbox') {
                    if ($array[$customField->alias]) {
                        $options = StringUtil::deserialize($customField->options);
                        $values = StringUtil::deserialize($array[$customField->alias]);
                        $displayValues = [];
                        foreach ($values as $value) {
                            foreach ($options as $option) {
                                if ($option['key'] === $value) {
                                    $displayValues[] = $option['value'];
                                }
                            }
                        }
                        if (!empty($displayValues)) {
                            $array[$customField->alias] = $customField->name . ": " . implode(', ', $displayValues);
                        }
                    }
                }
            }
        }

        return ArrayHelper::arrayToObject($array);
    }
}