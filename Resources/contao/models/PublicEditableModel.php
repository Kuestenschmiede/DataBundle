<?php


namespace con4gis\MapContentBundle\Resources\contao\models;


use con4gis\ProjectsBundle\Classes\Common\C4GBrickCommon;
use Contao\StringUtil;

class PublicEditableModel
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

        $businessHours = StringUtil::deserialize($array['businessHours']);
        $array['businessHours'] = json_encode($businessHours);
        $array['businessHours'] = str_replace('"', '\'', $array['businessHours']);

        return C4GBrickCommon::arrayToObject($array);
    }
}