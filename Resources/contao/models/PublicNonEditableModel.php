<?php


namespace con4gis\MapContentBundle\Resources\contao\models;


use con4gis\MapContentBundle\Resources\contao\modules\PublicNonEditableModule;
use con4gis\ProjectsBundle\Classes\Common\C4GBrickCommon;

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
            $resultElements[$key]['type'] = $types[$re['type']];
            $resultElements[$key]['addressName'] = $re['addressName'];
            if ($re['addressStreetNumber'] !== '0') {
                $resultElements[$key]['addressStreet'] = $re['addressStreet'] . ' ' . $re['addressStreetNumber'];
            } else {
                $resultElements[$key]['addressStreet'] = $re['addressStreet'];
            }
            $resultElements[$key]['addressCity'] = $re['addressZip'] . ' ' . $re['addressCity'];

//            $businessTimes = \StringUtil::deserialize($re['businessHours']);
//            $re['businessHours'] = '';
//            foreach ($businessTimes as $k => $time) {
//                $timeString[$k] = '';
//                if ($time['dayFrom'] !== '' && $time['timeFrom'] !== '' && $time['timeTo'] !== '') {
//                    $timeString[$k] .= $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_reference'][$time['dayFrom']];
//                    if ($time['dayTo'] !== $time['dayFrom'] && $time['dayTo'] !== '') {
//                        if (abs(intval($time['dayTo']) - intval($time['dayFrom'])) > 1) {
//                            $join = $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_join']['to'];
//                        } else {
//                            $join = $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_join']['and'];
//                        }
//
//                        $timeString[] .= " $join " . $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_reference'][$time['dayTo']];
//                    }
//                    $timeString[$k] .= ": " . date('H:i', $time['timeFrom']) . " - " . date('H:i', $time['timeTo']);
//                }
//            }
//
//            if ($re['businessHoursAdditionalInfo'] !== '') {
//                $timeString[] = $re['businessHoursAdditionalInfo'];
//            }
//
//            foreach ($timeString as $string) {
//                $re['businessHours'] = "<li class=\"c4g_brick_list_column c4g_brick_list_row_column fax\">$string</li>";
//            }
        }

        return C4GBrickCommon::arrayToObject($resultElements);
    }

    public static function findByPk($pk) {
        $model = MapcontentElementModel::findByPk($pk);
        $array = $model->row();
        $address = [];
        if ($array['addressStreet'] !== '' && $array['addressStreetNumber'] !== '0') {
            $array['addressStreet'] = $array['addressStreet']. ' ' . $array['addressStreetNumber'];
        }
        if ($array['addressZip'] !== '' && $array['addressCity'] !== '') {
            $array['addressCity'] = $array['addressZip'] . ' ' . $array['addressCity'];
        }
        $array['address'] = implode(', ',  $address);

        $businessTimes = \StringUtil::deserialize($array['businessHours']);
        $array['businessHours'] = '';
        foreach ($businessTimes as $k => $time) {
            $timeString[] = '';
            if ($time['dayFrom'] !== '' && $time['timeFrom'] !== '' && $time['timeTo'] !== '') {
                $timeString[] .= $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_reference'][$time['dayFrom']];
                if ($time['dayTo'] !== $time['dayFrom'] && $time['dayTo'] !== '') {
                    if (abs(intval($time['dayTo']) - intval($time['dayFrom'])) > 1) {
                        $join = $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_join']['to'];
                    } else {
                        $join = $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_join']['and'];
                    }

                    $timeString[] .= " $join " . $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_reference'][$time['dayTo']];
                }
                $timeString[] .= ": " . date('H:i', $time['timeFrom']) . " - " . date('H:i', $time['timeTo']);
            }
        }

        if ($array['businessHoursAdditionalInfo'] !== '') {
            $timeString[] = $array['businessHoursAdditionalInfo'];
        }

        foreach ($timeString as $string) {
            $re['businessHours'] = "<li class=\"c4g_brick_list_column c4g_brick_list_row_column fax\">$string</li>";
        }

        return C4GBrickCommon::arrayToObject($array);
    }
}