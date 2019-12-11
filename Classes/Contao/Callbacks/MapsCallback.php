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

use con4gis\MapContentBundle\Resources\contao\models\MapcontentDirectoryModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use Contao\Backend;

class MapsCallback extends Backend
{
    public function getConfiguredTypes()
    {
        $arrTypes = [];
        $t = "tl_c4g_mapcontent_type";
        $arrOptions = array(
            'order' => "$t.categorySort ASC, $t.name ASC"
        );
        $types = MapcontentTypeModel::findAll($arrOptions);
        foreach ($types as $type) {
            $arrTypes[$type->id] = $type->name;
        }
        return $arrTypes;
    }

    public function getConfiguredDirectories()
    {
        $arrTypes = [];
        $t = "tl_c4g_mapcontent_directory";
        $arrOptions = array(
            'order' => "$t.name ASC"
        );
        $types = MapcontentDirectoryModel::findAll($arrOptions);
        foreach ($types as $type) {
            $arrTypes[$type->id] = $type->name;
        }
        return $arrTypes;
    }
}