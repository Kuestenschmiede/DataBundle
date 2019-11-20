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
use Contao\StringUtil;
use Contao\System;

class MapcontentCustomFieldCallback extends Backend
{
    private $dcaName = 'tl_c4g_mapcontent_custom_fields';

    public function getLabels($row) {
        $labels['name'] = $row['name'];
        $labels['type'] = $GLOBALS['TL_LANG']['mapcontent_custom_field_types'][$row['type']];
        return $labels;
    }

    public function loadTypes($dca) {
        return $GLOBALS['TL_LANG']['mapcontent_custom_field_types'];
    }

    public function loadClassOptions($dca) {
        return $GLOBALS['TL_LANG']['mapcontent_custom_field_class_options'];
    }

    public function loadDefaultOptions($dca) {
        $options = StringUtil::deserialize($dca->activeRecord->options);
        $formattedOptions = [];
        foreach ($options as $option) {
            $formattedOptions[$option['key']] = $option['value'];
        }
        return $formattedOptions;
    }

    public function saveAlias($value, DataContainer $dca) {
        if (strval($value) !== '') {
            return strtolower(str_replace(' ', '_', $value));
        } else {
            return strtolower(str_replace(' ', '_', $dca->activeRecord->name));
        }
    }
}