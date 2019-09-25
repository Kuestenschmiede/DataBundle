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

use con4gis\MapContentBundle\Resources\contao\models\MapcontentLocationModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTagModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use Contao\Backend;
use Contao\DataContainer;
use Contao\StringUtil;

class MapcontentElementCallback extends Backend
{
    private $dcaName = 'tl_c4g_mapcontent_element';

    public function loadLocations()
    {
        $arrLocations = [];
        $locations = MapcontentLocationModel::findAll();
        foreach ($locations as $location) {
            $arrLocations[$location->id] = $location->name;
        }
        return $arrLocations;
    }
    
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

    public function getLabel($arrRow){
        $label['name'] = $arrRow['name'];
        $label['location'] = MapcontentLocationModel::findByPk($arrRow['location'])->name;
        $label['type'] = MapcontentTypeModel::findByPk($arrRow['type'])->name;
        return $label;
    }

    public function getDay($dc) {
        return $GLOBALS['con4gis']['map-content']['day_option'];
    }
}