<?php
/**
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package   	con4gis
 * @version    7
 * @author  	    con4gis contributors (see "authors.txt")
 * @license 	    LGPL-3.0-or-later
 * @copyright 	Küstenschmiede GmbH Software & Design
 * @link              https://www.con4gis.org
 */
namespace con4gis\DataBundle\Classes\Contao\Callbacks;

use con4gis\DataBundle\Resources\contao\models\DataElementModel;
use con4gis\DataBundle\Resources\contao\models\DataTypeModel;
use con4gis\MapsBundle\Classes\Utils;
use Contao\Backend;
use Contao\DataContainer;

class ElementCallback extends Backend
{
    private $dcaName = 'tl_c4g_data_element';

    public function loadTypes()
    {
        $arrTypes = [];
        $arrTypes[''] = '-';
        $types = DataTypeModel::findAll();
        foreach ($types as $type) {
            if ($type->name !== '') {
                $arrTypes[$type->id] = $type->name;
            }
        }

        return $arrTypes;
    }

    public function loadParentOptions(DataContainer $dc)
    {
        $options = [];
        $id = $dc->activeRecord->id;
        if (!$id) {
            return [];
        }
        $models = DataElementModel::findAll();
        foreach ($models as $model) {
            if ($model->id !== $id) {
                $options[$model->id] = $model->name;
            }
        }

        return $options;
    }

    public function getLabel($arrRow)
    {
        $label['name'] = $arrRow['name'];
        $label['type'] = DataTypeModel::findByPk($arrRow['type'])->name;

        return $label;
    }

    public function getDay($dc)
    {
        return $GLOBALS['con4gis']['data']['day_option'];
    }

    public function changeFileBinToUuid($fieldValue, DataContainer $dc)
    {
        return \StringUtil::binToUuid($fieldValue);
    }

    public function saveDate($value, DataContainer $dca)
    {
        return strtotime($value);
    }

    public function loadDate($value, DataContainer $dca)
    {
        return date('m/d/Y', $value);
    }

    /**
     * Validate Location Lon
     */
    public function setLocLon($varValue, DataContainer $dc)
    {
        if (!Utils::validateLon($varValue)) {
            throw new \Exception($GLOBALS['TL_LANG']['c4g_maps']['geox_invalid']);
        }

        return $varValue;
    }

    /**
     * Validate Location Lat
     */
    public function setLocLat($varValue, DataContainer $dc)
    {
        if (!Utils::validateLat($varValue)) {
            throw new \Exception($GLOBALS['TL_LANG']['c4g_maps']['geoy_invalid']);
        }

        return $varValue;
    }
}
