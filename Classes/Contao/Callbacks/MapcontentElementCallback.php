<?php
/**
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package   	con4gis
 * @version        6
 * @author  	    con4gis contributors (see "authors.txt")
 * @license 	    LGPL-3.0-or-later
 * @copyright 	Küstenschmiede GmbH Software & Design
 * @link              https://www.con4gis.org
 *
 */

namespace con4gis\MapContentBundle\Classes\Contao\Callbacks;

use con4gis\MapContentBundle\Resources\contao\models\MapcontentElementModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTagModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use con4gis\MapsBundle\Resources\contao\classes\Utils;
use Contao\Backend;
use Contao\DataContainer;

class MapcontentElementCallback extends Backend
{
    private $dcaName = 'tl_c4g_mapcontent_element';
    
    public function loadTypes()
    {
        $arrTypes = [];
        $types = MapcontentTypeModel::findAll();
        foreach ($types as $type) {
            $arrTypes[$type->id] = $type->name;
        }
        return $arrTypes;
    }
    
    public function loadAvailableTags(DataContainer $dc)
    {
        $arrTags = [];
        $typeId = $dc->activeRecord->type;
        if (!$typeId) {
            return [];
        } else {
            $type = MapcontentTypeModel::findByPk($typeId);
            if (!$type) {
                return [];
            }
            $tagIds = unserialize($type->availableTags);
            $tags = MapcontentTagModel::findMultipleByIds($tagIds);
            foreach ($tags as $tag) {
                $arrTags[$tag->id] = $tag->name;
            }
            return $arrTags;
        }
    }

    public function loadParentOptions(DataContainer $dc) {
        $options = [];
        $id = $dc->activeRecord->id;
        if (!$id) {
            return [];
        } else {
            $models = MapcontentElementModel::findAll();
            foreach ($models as $model) {
                if ($model->id !== $id) {
                    $options[$model->id] = $model->name;
                }
            }
        }
        return $options;
    }

    public function getLabel($arrRow){
        $label['name'] = $arrRow['name'];
//        $label['location'] = MapcontentLocationModel::findByPk($arrRow['location'])->name;
        $label['type'] = MapcontentTypeModel::findByPk($arrRow['type'])->name;
        return $label;
    }

    public function getDay($dc) {
        return $GLOBALS['con4gis']['map-content']['day_option'];
    }

    public function changeFileBinToUuid($fieldValue, DataContainer $dc) {
        return \StringUtil::binToUuid($fieldValue);
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