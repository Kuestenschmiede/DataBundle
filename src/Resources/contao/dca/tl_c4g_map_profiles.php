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

use con4gis\DataBundle\Classes\Contao\Callbacks\MapsCallback;

$cbClass = MapsCallback::class;

$GLOBALS['TL_DCA']['tl_c4g_map_profiles']['palettes']['default'] = str_replace("filters,filterHandling,filterResetButton,", "filters,filterHandling,filterResetButton,filterTypeData", $GLOBALS['TL_DCA']['tl_c4g_map_profiles']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_c4g_map_profiles']['palettes']['default'] = str_replace("{geosearch_legend:hide},geosearch_headline,geosearch_engine,geosearchParams", "{geosearch_legend:hide},geosearch_headline,geosearch_engine,ownGeosearch,searchFields,geosearchParams", $GLOBALS['TL_DCA']['tl_c4g_map_profiles']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_c4g_map_profiles']['fields']['filterTypeData'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['filterTypeData'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default '0'"
];
$GLOBALS['TL_DCA']['tl_c4g_map_profiles']['fields']['ownGeosearch'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['ownGeosearch'],
    'exclude'                 => true,
    'default'                 => false,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default ''"
];
$GLOBALS['TL_DCA']['tl_c4g_map_profiles']['fields']['searchFields'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['searchFields'],
    'exclude'                 => true,
    'default'                 => 'a:0:{}',
    'inputType'               => 'multiColumnWizard',
    'eval'                    => [
        'columnsCallback'     => [$cbClass,'getSearchFields']
    ],
    'sql'                     => "blob"
];