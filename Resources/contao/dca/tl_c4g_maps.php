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

use con4gis\MapContentBundle\Classes\Contao\Callbacks\MapsCallback;

$cbClass = MapsCallback::class;

$GLOBALS['TL_DCA']['tl_c4g_maps']['palettes']['mpCntnt'] = "{general_legend},name,profile,profile_mobile,published;{map_legend},is_map;{location_legend},location_type,tDontShowIfEmpty,data_layername,typeSelection,data_hidelayer,hide_when_in_tab;{protection_legend:hide},protect_element;";

$GLOBALS['TL_DCA']['tl_c4g_maps']['fields']['typeSelection'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['typeSelection'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'eval'                    => ['mandatory'=>true, 'tl_class'=>'clr', 'chosen' => true, 'multiple' => true],
    'options_callback'        => [$cbClass, 'getConfiguredTypes'],
    'sql'                     => "blob NULL default ''"
];