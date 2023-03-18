<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2022, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

use con4gis\CoreBundle\Classes\C4GVersionProvider;
use con4gis\DataBundle\Classes\Contao\Callbacks\MapsCallback;

$cbClass = MapsCallback::class;

//ToDo map implementation
$routing = '';

if (C4GVersionProvider::isInstalled('con4gis/maps')) {
    $routing = ',routing_to';
}

$GLOBALS['TL_DCA']['tl_c4g_maps']['palettes']['mpCntnt'] = "{general_legend},name,location_type;{location_legend},initial_opened,filterByBaseLayer,tDontShowIfEmpty,data_layername,typeSelection,data_hidelayer,hide_when_in_tab,exemptFromFilter,exemptFromRealFilter,hideInStarboard,zoomTo".$routing.";{protection_legend:hide},protect_element;{expert_legend:hide},directLink,excludeFromSingleLayer,be_optimize_checkboxes_limit;";
$GLOBALS['TL_DCA']['tl_c4g_maps']['palettes']['mpCntnt_directory'] = "{general_legend},name,location_type;{location_legend},initial_opened,filterByBaseLayer,tDontShowIfEmpty,data_layername,directorySelection,data_hidelayer,hide_when_in_tab,exemptFromFilter,exemptFromRealFilter,hideInStarboard,zoomTo;{protection_legend:hide},protect_element;{expert_legend:hide},directLink,excludeFromSingleLayer,be_optimize_checkboxes_limit;";

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
$GLOBALS['TL_DCA']['tl_c4g_maps']['fields']['directLink'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['directLink'],
    'exclude'                 => true,
    'default'                 => false,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default ''"
];
$GLOBALS['TL_DCA']['tl_c4g_maps']['fields']['zoomTo'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['zoomTo'],
    'exclude'                 => true,
    'default'                 => false,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default ''"
];