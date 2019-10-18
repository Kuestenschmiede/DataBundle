<?php

use con4gis\MapContentBundle\Resources\contao\models\MapcontentTagModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentElementModel;
use con4gis\MapContentBundle\Resources\contao\modules\PublicNonEditableModule;

$GLOBALS['con4gis']['mapcontent_types'][] = 'default';

$GLOBALS['con4gis']['map-content']['day_option'] = [
    '0', '1', '2', '3', '4', '5', '6'
];

$GLOBALS['con4gis']['map-content']['frontend']['address']['default'] = false;
$GLOBALS['con4gis']['map-content']['frontend']['contact']['default'] = false;
$GLOBALS['con4gis']['map-content']['frontend']['accessibility']['default'] = false;
$GLOBALS['con4gis']['map-content']['frontend']['image']['default'] = false;

$GLOBALS['BE_MOD']['con4gis_maps']['c4g_mapcontent_tag'] = [
    'tables' => ['tl_c4g_mapcontent_tag'],
];

$GLOBALS['BE_MOD']['con4gis_maps']['c4g_mapcontent_type'] = [
    'tables' => ['tl_c4g_mapcontent_type'],
];

$GLOBALS['BE_MOD']['con4gis_maps']['c4g_mapcontent_element'] = [
    'tables' => ['tl_c4g_mapcontent_element'],
    'javascript' => ['/bundles/con4giseditor/js/c4g-backend-helper.js']
];

$GLOBALS['c4g_locationtypes'][] = 'mpCntnt';

$GLOBALS['TL_MODELS']['tl_c4g_mapcontent_tag'] = MapcontentTagModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_mapcontent_type'] = MapcontentTypeModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_mapcontent_element'] = MapcontentElementModel::class;

array_insert($GLOBALS['FE_MOD']['con4gis_mapcontent'], 1,
    ['public_noneditable' => PublicNonEditableModule::class]
);