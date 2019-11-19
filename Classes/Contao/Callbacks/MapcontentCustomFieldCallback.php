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
use Contao\System;

class MapcontentCustomFieldCallback extends Backend
{
    private $dcaName = 'tl_c4g_mapcontent_custom_fields';

    public function loadTypes($dca) {
        return $GLOBALS['TL_LANG']['mapcontent_custom_field_types'];
    }

    public function loadClassOptions($dca) {
        return $GLOBALS['TL_LANG']['mapcontent_custom_field_class_options'];
    }
}