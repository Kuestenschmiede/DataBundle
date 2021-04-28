<?php
$GLOBALS['TL_DCA']['tl_c4g_map_profiles']['palettes']['default'] = str_replace("filters,filterHandling,filterResetButton", "filters,filterHandling,filterResetButton,filterTypeData", $GLOBALS['TL_DCA']['tl_c4g_map_profiles']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_c4g_map_profiles']['fields']['filterTypeData'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['filterTypeData'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default '0'"
];
