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


use con4gis\MapContentBundle\Resources\contao\models\MapcontentTagModel;
use con4gis\MapsBundle\Resources\contao\models\C4gMapLocstylesModel;
use Contao\Backend;
use Contao\StringUtil;

class MapcontentTypeCallback extends Backend
{
    
    public function getLocstyles()
    {
        $locstyles = C4gMapLocstylesModel::findAll();
        $arrLocstyles = [];
        foreach ($locstyles as $locstyle) {
            $arrLocstyles[$locstyle->id] = $locstyle->name;
        }
        return $arrLocstyles;
    }
    
    public function getAvailableTags()
    {
        $tags = MapcontentTagModel::findAll();
        $arrTags = [];
        foreach ($tags as $tag) {
            $arrTags[$tag->id] = $tag->name;
        }
        return $arrTags;
    }

    public function getLabel($arrRow){
        $label['name'] = $arrRow['name'];
        $label['type'] = $GLOBALS['con4gis']['mapcontent_types'][$arrRow['type']];
        $label['availableTags'] = '';
        foreach (StringUtil::deserialize($arrRow['availableTags']) as $tag) {
            $model = MapcontentTagModel::findByPk($tag);
            if ($label['availableTags'] === '') {
                $label['availableTags'] = $model->name;
            } else {
                $label['availableTags'] .= ', ' . $model->name;
            }
        }
        return $label;
    }
}