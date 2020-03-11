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
 *
 */

use con4gis\CoreBundle\Classes\C4GVersionProvider;
use con4gis\DataBundle\Classes\Contao\Callbacks\MapsCallback;

$cbClass = MapsCallback::class;

//ToDo map implementation
$routing = '';
/*
if (C4GVersionProvider::isInstalled('con4gis/routing')) {
    $routing = ',routing_to';
}*/

$GLOBALS['TL_DCA']['tl_c4g_maps']['palettes']['mpCntnt'] = "{general_legend},name,location_type;{location_legend},tDontShowIfEmpty,data_layername,typeSelection,data_hidelayer,hide_when_in_tab,exemptFromFilter,exemptFromRealFilter,hideInStarboard".$routing.";{protection_legend:hide},protect_element;{expert_legend:hide},excludeFromSingleLayer,be_optimize_checkboxes_limit;";
$GLOBALS['TL_DCA']['tl_c4g_maps']['palettes']['mpCntnt_directory'] = "{general_legend},name,location_type;{location_legend},tDontShowIfEmpty,data_layername,directorySelection,data_hidelayer,hide_when_in_tab,exemptFromFilter,exemptFromRealFilter,hideInStarboard;{protection_legend:hide},protect_element;{expert_legend:hide},excludeFromSingleLayer,be_optimize_checkboxes_limit;";

$GLOBALS['TL_DCA']['tl_c4g_maps']['fields']['typeSelection'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['typeSelection'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'eval'                    => ['mandatory'=>true, 'tl_class'=>'clr', 'chosen' => true, 'multiple' => true],
    'options_callback'        => [$cbClass, 'getConfiguredTypes'],
    'sql'                     => "blob NULL default ''"
];

$GLOBALS['TL_DCA']['tl_c4g_maps']['fields']['directorySelection'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['directorySelection'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'eval'                    => ['mandatory'=>true, 'tl_class'=>'clr', 'chosen' => true, 'multiple' => true],
    'options_callback'        => [$cbClass, 'getConfiguredDirectories'],
    'sql'                     => "blob NULL default ''"
];