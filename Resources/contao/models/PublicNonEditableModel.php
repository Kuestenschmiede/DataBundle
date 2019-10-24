<?php


namespace con4gis\MapContentBundle\Resources\contao\models;


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

        $stmtElements = $db->prepare("SELECT * FROM tl_c4g_mapcontent_element WHERE name != '' ORDER BY name ASC");
        $resultElements = $stmtElements->execute()->fetchAllAssoc();

        foreach ($resultElements as $key => $re) {
            $resultElements[$key]['type'] = $types[$re['type']];
            $resultElements[$key]['addressName'] = $re['addressName'];
            if ($re['addressStreetNumber'] !== '0') {
                $resultElements[$key]['addressStreet'] = $re['addressStreet'] . ' ' . $re['addressStreetNumber'];
            } else {
                $resultElements[$key]['addressStreet'] = $re['addressStreet'];
            }
            $resultElements[$key]['addressCity'] = $re['addressZip'] . ' ' . $re['addressCity'];
        }

        return C4GBrickCommon::arrayToObject($resultElements);
    }

    public static function findByPk($pk) {
        $model = MapcontentElementModel::findByPk($pk);
        $array = $model->row();
        $address = [];
        if ($array['addressName'] !== '') {
            $address[] = $array['addressName'];
        }
        if ($array['addressStreet'] !== '' && $array['addressStreetNumber'] !== '0') {
            $address[] = $array['addressStreet']. ' ' . $array['addressStreetNumber'];
        }
        if ($array['addressZip'] !== '' && $array['addressCity'] !== '') {
            $address[] = $array['addressZip'] . ' ' . $array['addressCity'];
        }
        $array['address'] = implode(', ',  $address);
        return C4GBrickCommon::arrayToObject($array);
    }
}