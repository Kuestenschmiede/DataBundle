<?php

use con4gis\MapContentBundle\Resources\contao\models\MapcontentLocationModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTagModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentElementModel;

$GLOBALS['con4gis']['mapcontent_types'][] = 'default';

$GLOBALS['con4gis']['map-content']['day_option'] = [
    '0', '1', '2', '3', '4', '5', '6'
];

$GLOBALS['BE_MOD']['con4gis_maps']['c4g_mapcontent_location'] = [
    'tables' => ['tl_c4g_mapcontent_location'],
    'javascript' => ['/bundles/con4giseditor/js/c4g-backend-helper.js']
];

$GLOBALS['BE_MOD']['con4gis_maps']['c4g_mapcontent_tag'] = [
    'tables' => ['tl_c4g_mapcontent_tag'],
];

$GLOBALS['BE_MOD']['con4gis_maps']['c4g_mapcontent_type'] = [
    'tables' => ['tl_c4g_mapcontent_type'],
];

$GLOBALS['BE_MOD']['con4gis_maps']['c4g_mapcontent_element'] = [
    'tables' => ['tl_c4g_mapcontent_element'],
];

$GLOBALS['c4g_locationtypes'][] = 'mpCntnt';

$GLOBALS['TL_MODELS']['tl_c4g_mapcontent_location'] = MapcontentLocationModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_mapcontent_tag'] = MapcontentTagModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_mapcontent_type'] = MapcontentTypeModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_mapcontent_element'] = MapcontentElementModel::class;