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

array_insert($GLOBALS['BE_MOD'], array_search('con4gis', array_keys($GLOBALS['BE_MOD'])) + 3,
    ['con4gis_mapcontent' => [
        'c4g_mapcontent_type' => [
            'tables' => ['tl_c4g_mapcontent_type'],
            'stylesheet' => 'bundles/con4gismapcontent/css/backend_map_content_type.css'
        ],
        'c4g_mapcontent_element' => [
            'tables' => ['tl_c4g_mapcontent_element'],
            'javascript' => '/bundles/con4giseditor/js/c4g-backend-helper.js',
            'stylesheet' => [
                'bundles/con4gismapcontent/css/backend_map_content_element.css'
            ]
        ],
        'c4g_mapcontent_custom_field' => [
            'tables' => ['tl_c4g_mapcontent_custom_field']
        ],
        'c4g_mapcontent_directory' => [
            'tables' => ['tl_c4g_mapcontent_directory']
        ]
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