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
namespace con4gis\MapContentBundle\Classes\Contao\Callbacks;

use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use Contao\Backend;

class MapcontentDirectoryCallback extends Backend
{
    public function loadTypeOptions($dc)
    {
        $types = MapcontentTypeModel::findAll();
        $options = [];
        if ($types !== null) {
            foreach ($types as $type) {
                if (intval($type->id) > 0 && strval($type->name) !== '') {
                    $options[$type->id] = $type->name;
                }
            }
        }

        return $options;
    }
}
