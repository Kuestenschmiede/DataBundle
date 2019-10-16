<?php


namespace con4gis\MapContentBundle\Resources\contao\models;


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

        $stmtElements = $db->prepare("SELECT * FROM tl_c4g_mapcontent_element");
        $resultElements = $stmtElements->execute()->fetchAllAssoc();

        foreach ($resultElements as $key => $re) {
            $resultElements[$key]['type'] = $types[$re['type']];
            $address = [];
            if ($re['addressName'] !== '') {
                $address[] = $re['addressName'];
            }
            if ($re['addressStreet'] !== '') {
                $address[] = $re['addressStreet'];
            }
            if ($re['addressStreetNumber'] !== '0') {
                $address[] = $re['addressStreetNumber'];
            }
            if ($re['addressZip'] !== '' && $re['addressCity'] !== '') {
                $address[] = $re['addressZip'] . ' ' . $re['addressCity'];
            }
            $resultElements[$key]['address'] = implode(', ',  $address);
            $resultElements[$key]['addressName'] = $re['addressName'];
            $resultElements[$key]['addressStreet'] = $re['addressStreet'];
            $resultElements[$key]['addressStreetNumber'] = $re['addressStreetNumber'];
            $resultElements[$key]['addressZip'] = $re['addressZip'];
            $resultElements[$key]['addressCity'] = $re['addressCity'];
        }

        return C4GBrickCommon::arrayToObject($resultElements);
    }

    public static function findByPk($pk) {
        $model = MapcontentElementModel::findByPk($pk);
        $array = $model->row();

        $db = \Database::getInstance();
        $stmtTypes = $db->prepare("SELECT * FROM tl_c4g_mapcontent_type");
        $resultTypes = $stmtTypes->execute()->fetchAllAssoc();
        $types = [];
        foreach ($resultTypes as $rt) {
            $types[$rt['id']] = $rt['name'];
        }

        return C4GBrickCommon::arrayToObject($array);
    }

    public static function addressCondition($value) {
        $model = MapcontentTypeModel::findByPk($value);
        if ($model !== null) {
            $type = $model->type;
            return $GLOBALS['con4gis']['map-content']['frontend']['address'][$type] ? true : false;
        } else {
            return false;
        }
    }
}