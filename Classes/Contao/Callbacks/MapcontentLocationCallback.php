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
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use con4gis\MapsBundle\Resources\contao\classes\Utils;
use Contao\Backend;
use DataContainer;
use Exception;

class MapcontentLocationCallback extends Backend
{
    

    public function getLabel($arrRow){
        $label['name'] = $arrRow['name'];
        $label['loctype'] = $GLOBALS['TL_LANG']['tl_c4g_mapcontent_location']['loctype_ref'][$arrRow['loctype']];
        if ($arrRow['loctype'] == 'point') {
            $label['geox'] = $arrRow['geox'];
            $label['geoy'] = $arrRow['geoy'];
        } else {
            $label['geox'] = '-';
            $label['geoy'] = '-';
        }
        return $label;
    }
}