<?php

use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentElementModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentCustomFieldModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentDirectoryModel;
use con4gis\MapContentBundle\Resources\contao\modules\PublicEditableModule;
use con4gis\MapContentBundle\Resources\contao\modules\PublicNonEditableModule;

$GLOBALS['con4gis']['mapcontent_types'][] = 'default';

$GLOBALS['con4gis']['mapcontent_custom_field_types'][] = 'text';
$GLOBALS['con4gis']['mapcontent_custom_field_types'][] = 'textarea';
$GLOBALS['con4gis']['mapcontent_custom_field_types'][] = 'texteditor';
$GLOBALS['con4gis']['mapcontent_custom_field_types'][] = 'natural';
$GLOBALS['con4gis']['mapcontent_custom_field_types'][] = 'int';
$GLOBALS['con4gis']['mapcontent_custom_field_types'][] = 'select';
$GLOBALS['con4gis']['mapcontent_custom_field_types'][] = 'checkbox';
$GLOBALS['con4gis']['mapcontent_custom_field_types'][] = 'icon';
$GLOBALS['con4gis']['mapcontent_custom_field_types'][] = 'multicheckbox';
$GLOBALS['con4gis']['mapcontent_custom_field_types'][] = 'datepicker';
$GLOBALS['con4gis']['mapcontent_custom_field_types'][] = 'link';
$GLOBALS['con4gis']['mapcontent_custom_field_types'][] = 'legend';

$GLOBALS['con4gis']['map-content']['day_option'] = [
    '0', '1', '2', '3', '4', '5', '6'
];

$GLOBALS['con4gis']['map-content']['frontend']['address']['default'] = false;
$GLOBALS['con4gis']['map-content']['frontend']['contact']['default'] = false;
$GLOBALS['con4gis']['map-content']['frontend']['accessibility']['default'] = false;
$GLOBALS['con4gis']['map-content']['frontend']['image']['default'] = false;

$GLOBALS['BE_MOD']['con4gis'] = array_merge($GLOBALS['BE_MOD']['con4gis'], [
    'c4g_mapcontent_custom_field' => [
        'brick' => 'map-content',
        'tables' => ['tl_c4g_mapcontent_custom_field'],
        'icon' => 'bundles/con4giscore/images/be-icons/edit.svg'
    ],
    'c4g_mapcontent_element' => [
        'brick' => 'map-content',
        'tables' => ['tl_c4g_mapcontent_element'],
        'javascript' => '/bundles/con4giseditor/js/c4g-backend-helper.js',
        'stylesheet' => [
            'bundles/con4gismapcontent/css/backend_map_content_element.css'
        ],
        'icon' => $icon = 'bundles/con4gismapcontent/images/be-icons/mapelements.svg'
    ],
    'c4g_mapcontent_type' => [
        'brick' => 'map-content',
        'tables' => ['tl_c4g_mapcontent_type'],
        'stylesheet' => 'bundles/con4gismapcontent/css/backend_map_content_type.css',
        'icon' => 'bundles/con4gismapcontent/images/be-icons/mapcategory.svg'
    ],
    'c4g_mapcontent_directory' => [
        'brick' => 'map-content',
        'tables' => ['tl_c4g_mapcontent_directory'],
        'icon' => 'bundles/con4gismapcontent/images/be-icons/mapfolder.svg'
    ]
]);

if(TL_MODE == "BE") {
    $GLOBALS['TL_CSS'][] = '/bundles/con4gismapcontent/css/con4gis.css';
}

$GLOBALS['c4g_locationtypes'][] = 'mpCntnt';
$GLOBALS['c4g_locationtypes'][] = 'mpCntnt_directory';

$GLOBALS['TL_MODELS']['tl_c4g_mapcontent_type'] = MapcontentTypeModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_mapcontent_element'] = MapcontentElementModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_mapcontent_custom_field'] = MapcontentCustomFieldModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_mapcontent_directory'] = MapcontentDirectoryModel::class;

//array_insert($GLOBALS['FE_MOD']['con4gis_mapcontent'], 1,
//    ['public_editable' => PublicEditableModule::class]
//);

array_insert($GLOBALS['FE_MOD']['con4gis_mapcontent'], 1,
    ['public_noneditable' => PublicNonEditableModule::class]
);