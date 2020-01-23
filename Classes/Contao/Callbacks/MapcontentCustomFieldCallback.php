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

use Contao\Backend;
use Contao\DataContainer;
use Contao\StringUtil;
use Contao\Message;

class MapcontentCustomFieldCallback extends Backend
{
    private $dcaName = 'tl_c4g_mapcontent_custom_field';

    public function addHint()
    {
        Message::addInfo($GLOBALS['TL_LANG'][$this->dcaName]['install_tool_hint']);
    }

    public function getLabels($row)
    {
        $labels['name'] = $row['name'];
        $labels['type'] = $GLOBALS['TL_LANG']['mapcontent_custom_field_types'][$row['type']];

        return $labels;
    }

    public function loadTypes($dca)
    {
        return $GLOBALS['TL_LANG']['mapcontent_custom_field_types'];
    }

    public function loadClassOptions($dca)
    {
        return $GLOBALS['TL_LANG']['mapcontent_custom_field_class_options'];
    }

    public function loadDefaultOptions($dca)
    {
        $options = StringUtil::deserialize($dca->activeRecord->options);
        $formattedOptions = [];
        foreach ($options as $option) {
            $formattedOptions[$option['key']] = $option['value'];
        }

        return $formattedOptions;
    }

    public function loadFrontendFilterCheckboxStylingOptions($dca)
    {
        return $GLOBALS['TL_LANG']['mapcontent_custom_field_frontend_filter_checkbox_styling_options'];
    }

    public function saveAlias($value, DataContainer $dca)
    {
        if (strval($value) !== '') {
            return str_replace([' ', 'ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü'], ['_', 'ae', 'oe', 'ue', 'ae', 'oe', 'ue'], strtolower($value));
        }

        return str_replace([' ', 'ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü'], ['_', 'ae', 'oe', 'ue', 'ae', 'oe', 'ue'], strtolower($dca->activeRecord->name));
    }

    public function saveDate($value, DataContainer $dca)
    {
        return strtotime($value);
    }

    public function loadDate($value, DataContainer $dca)
    {
        return date('m/d/Y', $value);
    }
}
