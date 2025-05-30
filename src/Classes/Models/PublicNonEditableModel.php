<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2022, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

namespace con4gis\DataBundle\Classes\Models;

use con4gis\CoreBundle\Classes\Helper\ArrayHelper;
use con4gis\DataBundle\Classes\Events\LoadAdditionalListDataEvent;
use con4gis\DataBundle\Controller\PublicNonEditableController;
use Contao\Database;
use Contao\StringUtil;
use Exception;
use stdClass;

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
        $orderByFields = implode(', ', PublicNonEditableController::$orderByFields);

        if (PublicNonEditableController::$dataMode === '1' && !empty(PublicNonEditableController::$type)) {
            $resultElements = [];
            foreach (PublicNonEditableController::$type as $type) {
                $stmtElements = $db->prepare("SELECT tl_c4g_data_element.* FROM tl_c4g_data_element JOIN tl_c4g_data_type ON tl_c4g_data_element.type = tl_c4g_data_type.id WHERE (tl_c4g_data_element.name != '' OR tl_c4g_data_element.published = 1) AND tl_c4g_data_element.type = ? AND (tl_c4g_data_type.allowPublishing != 1 OR tl_c4g_data_element.published = 1) ORDER BY $orderByFields ASC");
                $resultElements = array_merge($resultElements, $stmtElements->execute($type)->fetchAllAssoc());
            }
        } else {
            if (PublicNonEditableController::$dataMode === '2' && !empty(PublicNonEditableController::$directory)) {
                $dirTypes = [];
                $resultElements = [];
                foreach (PublicNonEditableController::$directory as $directory) {
                    $directoryModel = DataDirectoryModel::findByPk($directory);
                    if ($directoryModel !== null) {
                        $dirTypes = array_merge($dirTypes, StringUtil::deserialize($directoryModel->types));
                    }
                }
                $dirTypes = array_unique($dirTypes);
                foreach ($dirTypes as $type) {
                    $stmtElements = $db->prepare("SELECT tl_c4g_data_element.* FROM tl_c4g_data_element JOIN tl_c4g_data_type ON tl_c4g_data_element.type = tl_c4g_data_type.id WHERE (tl_c4g_data_element.name != '' OR tl_c4g_data_element.published = 1) AND tl_c4g_data_element.type = ? AND (tl_c4g_data_type.allowPublishing != 1 OR tl_c4g_data_element.published = 1) ORDER BY $orderByFields ASC");
                    $resultElements = array_merge($resultElements, $stmtElements->execute($type)->fetchAllAssoc());
                }
            } else {
                $stmtElements = $db->prepare("SELECT tl_c4g_data_element.* FROM tl_c4g_data_element JOIN tl_c4g_data_type ON tl_c4g_data_element.type = tl_c4g_data_type.id WHERE (tl_c4g_data_element.name != '' OR tl_c4g_data_element.published = 1) AND (tl_c4g_data_type.allowPublishing != 1 OR tl_c4g_data_element.published = 1) ORDER BY $orderByFields ASC");
                $resultElements = $stmtElements->execute()->fetchAllAssoc();
            }
        }

        //ToDo find solution per sql for better performance
        $orderByFields = [];
        foreach (PublicNonEditableController::$orderByFields as $v) {
            $orderByFields[$v] = SORT_ASC;
        }
        $resultElements = ArrayHelper::sortArrayByFields($resultElements, $orderByFields);

        foreach ($resultElements as $key => $re) {

            if (intval($re['parentElement']) > 0) {
                $toMerge = [
                    $re
                ];
                while (intval($toMerge[0]['parentElement']) > 0) {
                    array_unshift($toMerge, DataElementModel::findByPk($toMerge[0]['parentElement'])->row());
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

            $directoryModels = DataDirectoryModel::findAll();
            $elementDirectories = [];
            if ($directoryModels !== null) {
                foreach ($directoryModels as $directoryModel) {
                    $directoryTypes = StringUtil::deserialize($directoryModel->types);
                    if (!empty($directoryTypes) && in_array($typeModel->id, $directoryTypes)) {
                        $elementDirectories[] = str_replace(' ', '', $directoryModel->name);
                    }
                }
            }

            $resultElements[$key]['directory'] = $elementDirectories;

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
                    $resultElements[$key]['address'] .= "<span class=\"span-list\">$part</span>";
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
                                $resultElements[$key][$model->alias] =  ($model->frontendName ?: $model->name) . "" . implode(', ', $displayValues);
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
                    } elseif ($model->type === 'datepicker') {
                        if (intval($resultElements[$key][$model->alias]) !== 0) {
                            $resultElements[$key][$model->alias] = date(
                                'd.m.Y',
                                $resultElements[$key][$model->alias]
                            );
                        } else {
                            $resultElements[$key][$model->alias] = '';
                        }
                    }
                }
            }

            $resultElements[$key]['searchInfo'] .= $resultElements[$key]['type'];
            $resultElements[$key]['searchInfoSubordinate'] = '';

            if ($resultElements[$key]['ownerGroupId'] > 0) {
                $groupModel = \Contao\MemberGroupModel::findByPk($resultElements[$key]['ownerGroupId']);
                if ($groupModel !== null) {
                    $resultElements[$key]['ownerGroupId'] = '<span class="list-label">' .
                        $GLOBALS['TL_LANG']['tl_c4g_data_element']['ownerGroupId'][0] . '</span>' .
                        '<span class="list_value">' . $groupModel->name . '</span>';
                } else {
                    $resultElements[$key]['ownerGroupId'] = '';
                }
            } else {
                $resultElements[$key]['ownerGroupId'] = '';
            }

            if ($resultElements[$key]['datePublished']) {
                $resultElements[$key]['datePublished'] = '<span class="list-label">' .
                    $GLOBALS['TL_LANG']['tl_c4g_data_element']['datePublished'][0] . '</span>' .
                    '<span class="list_value">' .
                    date('d.m.Y', $resultElements[$key]['datePublished']) .
                    '</span>';
            } else {
                $resultElements[$key]['datePublished'] = '';
            }
        }

        foreach ($resultElements as $key => $row) {
            $event = new LoadAdditionalListDataEvent($row);
            $dispatcher = \Contao\System::getContainer()->get('event_dispatcher');
            $dispatcher->dispatch($event, $event::NAME);
            $resultElements[$key] = $event->getRow();
        }

        if (PublicNonEditableController::$showLabelsInList) {
            foreach ($resultElements as $key => $row) {
                foreach ($row as $column => $value) {
                    if (is_string($value) && $value !== '') {
                        $customField = DataCustomFieldModel::findBy('alias', $column);
                        if ($customField !== null) {
                            if (
                                $customField->type === 'text' ||
                                $customField->type === 'select' ||
                                $customField->type === 'foreignKey' ||
                                $customField->type === 'datepicker'
                            ) {
                                $label = $customField->frontendName ?: $customField->name ?: '';
                                if ($label !== '') {
                                    $resultElements[$key][$column.'_value'] = $value;
                                    $resultElements[$key][$column] = '<span class="list-label">' . $label . '</span>' .
                                        '<span class="list_value">' . $value . '</span>';
                                }
                            } elseif ($customField->type === 'multicheckbox') {
                                $label = $customField->frontendName ?: $customField->name ?: '';
                                if ($label !== '') {
                                    $value = substr($value, strlen($label));
                                    $resultElements[$key][$column.'_value'] = $value;
                                    $resultElements[$key][$column] = '<span class="list-label">' . $label . '</span>' .
                                        '<span class="list_value">' . $value . '</span>';
                                }
                            }
                        }
                    }
                }
            }
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
        $array['itemType'] = strval($typeModel->itemType);

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
                            $array[$customField->alias] = ($customField->frontendName ?: $customField->name) . "" . implode(', ', $displayValues);
                        }
                    }
                } elseif ($customField->type === 'datepicker') {
                    if (intval($array[$customField->alias]) !== 0) {
                        $array[$customField->alias] = date(
                            'd.m.Y',
                            $array[$customField->alias]
                        );
                    } else {
                        $array[$customField->alias] = '';
                    }
                }
            }
        }

        return ArrayHelper::arrayToObject($array);
    }

    /**
     * @param string $field
     * @param string $value
     * @return bool|stdClass
     * @throws Exception
     */
    public static function findBy(string $field, string $value)
    {
        $database = Database::getInstance();
        $fields = $database->listFields('tl_c4g_data_element');
        $columns = array_column($fields, 'name');
        if (array_search($field, $columns) >= 0) {
            $statement = $database->prepare(
                "SELECT id FROM tl_c4g_data_element WHERE $field = ?"
            );
            $result = $statement->execute($value)->fetchAssoc();
            if ($result !== false) {
                return self::findByPk($result['id']);
            }
        }
//        throw new Exception();
        return false;
    }
}