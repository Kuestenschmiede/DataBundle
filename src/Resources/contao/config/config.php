<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2021, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

use con4gis\DataBundle\Classes\Models\DataTypeModel;
use con4gis\DataBundle\Classes\Models\DataElementModel;
use con4gis\DataBundle\Classes\Models\DataCustomFieldModel;
use con4gis\DataBundle\Classes\Models\DataDirectoryModel;
use con4gis\DataBundle\Classes\Models\MemberEditableModule;
use con4gis\DataBundle\Classes\Models\PublicNonEditableModule;

$GLOBALS['con4gis']['data_types'][] = 'default';

$GLOBALS['con4gis']['data_custom_field_types'][] = 'text';
$GLOBALS['con4gis']['data_custom_field_types'][] = 'textarea';
$GLOBALS['con4gis']['data_custom_field_types'][] = 'texteditor';
$GLOBALS['con4gis']['data_custom_field_types'][] = 'natural';
$GLOBALS['con4gis']['data_custom_field_types'][] = 'int';
$GLOBALS['con4gis']['data_custom_field_types'][] = 'select';
$GLOBALS['con4gis']['data_custom_field_types'][] = 'checkbox';
$GLOBALS['con4gis']['data_custom_field_types'][] = 'icon';
$GLOBALS['con4gis']['data_custom_field_types'][] = 'multicheckbox';
$GLOBALS['con4gis']['data_custom_field_types'][] = 'datepicker';
$GLOBALS['con4gis']['data_custom_field_types'][] = 'link';
$GLOBALS['con4gis']['data_custom_field_types'][] = 'legend';
$GLOBALS['con4gis']['data_custom_field_types'][] = 'foreignKey';

$GLOBALS['con4gis']['data']['day_option'] = [
    '0', '1', '2', '3', '4', '5', '6'
];

$GLOBALS['con4gis']['data']['frontend']['address']['default'] = false;
$GLOBALS['con4gis']['data']['frontend']['contact']['default'] = false;
$GLOBALS['con4gis']['data']['frontend']['accessibility']['default'] = false;
$GLOBALS['con4gis']['data']['frontend']['image']['default'] = false;

$GLOBALS['BE_MOD']['con4gis'] = array_merge($GLOBALS['BE_MOD']['con4gis'], [
    'c4g_data_custom_field' => [
        'brick' => 'data',
        'tables' => ['tl_c4g_data_custom_field'],
        'icon' => 'bundles/con4giscore/images/be-icons/edit.svg'
    ],
    'c4g_data_type' => [
        'brick' => 'data',
        'tables' => ['tl_c4g_data_type'],
        'stylesheet' => 'bundles/con4gisdata/dist/css/backend_data_type.min.css',
        'icon' => 'bundles/con4gisdata/images/be-icons/mapcategory.svg'
    ],
    'c4g_data_element' => [
        'brick' => 'data',
        'tables' => ['tl_c4g_data_element'],
        /*'javascript' => '/bundles/con4giseditor/js/c4g-backend-helper.js',*/
        'stylesheet' => [
            'bundles/con4gisdata/dist/css/backend_data_element.min.css'
        ],
        'icon' => $icon = 'bundles/con4gisdata/images/be-icons/mapelements.svg'
    ],
    'c4g_data_directory' => [
        'brick' => 'data',
        'tables' => ['tl_c4g_data_directory'],
        'icon' => 'bundles/con4gisdata/images/be-icons/mapfolder.svg'
    ]
]);

$GLOBALS['c4g_locationtypes'][] = 'mpCntnt';
$GLOBALS['c4g_locationtypes'][] = 'mpCntnt_directory';

$GLOBALS['TL_MODELS']['tl_c4g_data_type'] = DataTypeModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_data_element'] = DataElementModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_data_custom_field'] = DataCustomFieldModel::class;
$GLOBALS['TL_MODELS']['tl_c4g_data_directory'] = DataDirectoryModel::class;

//$GLOBALS['FE_MOD']['con4gis']['member_editable'] = MemberEditableModule::class;
//$GLOBALS['FE_MOD']['con4gis']['public_noneditable'] = PublicNonEditableModule::class;
//asort($GLOBALS['FE_MOD']['con4gis']);

$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = [\con4gis\DataBundle\Classes\Contao\Hooks\ReplaceInsertTags::class, 'replaceTag'];
