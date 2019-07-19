<?php
/**
 * con4gis - the gis-kit
 *
 * @version   php 7
 * @package   con4gis
 * @author    con4gis contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2019
 * @link      https://www.kuestenschmiede.de
 */

use con4gis\MapContentBundle\Classes\Contao\Callbacks\MapcontentLocationCallback;

$strName = 'tl_c4g_mapcontent_location';
$cbClass = MapcontentLocationCallback::class;

/**
 * Table tl_c4g_mapcontent_location
 */
$GLOBALS['TL_DCA'][$strName] =
[
    
    // Config
    'config' =>
    [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'sql' =>
        [
            'keys' =>
            [
                'id' => 'primary',
            ]
        ]
    ],
    'list' =>
    [
        'sorting' =>
        [
            'mode' => 2,
            'fields' => ['name'],
            'panelLayout' => 'filter;sort,search,limit',
            'headerFields' => [],
        ],
        'label' =>
        [
            'fields' => ['name', 'geox', 'geoy'],
            'showColumns' => true,
        ],
        'global_operations' =>
        [
            'all' =>
            [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            ]
        ],
        'operations' =>
        [
            'edit' =>
            [
                'label' => &$GLOBALS['TL_LANG'][$strName]['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif'
            ],
            'copy' =>
            [
                'label' => &$GLOBALS['TL_LANG'][$strName]['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif'
            ],
            'delete' =>
            [
                'label' => &$GLOBALS['TL_LANG'][$strName]['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ],
            'show' =>
            [
                'label' => &$GLOBALS['TL_LANG'][$strName]['show'],
                'href' => 'act=show',
                'icon' => 'show.gif'
            ]
        ]
    ],
    
    // Palettes
    'palettes' =>
    [
        '__selector__' => ['loctype'],
        'default' => '{data_legend},name,loctype'
    ],
    
    'subpalettes' =>
    [
        'loctype_point' => 'geox,geoy',
        'loctype_line' => 'geoJson',
        'loctype_circle' => 'geoJson',
        'loctype_polygon' => 'geoJson',
    ],
    
    // Fields
    'fields' =>
    [
        'id' =>
        [
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' =>
        [
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ],
        'name' =>
        [
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['name'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['tl_class'=>'clr', 'mandatory' => true],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ],
        'loctype' =>
        [
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['loctype'],
            'default'                 => 'point',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => ['point', 'circle', 'line', 'polygon'],
            'reference'               => $GLOBALS['TL_LANG'][$strName]['loctype_ref'],
            'eval'                    => ['tl_class'=>'clr', 'submitOnChange'=>true],
            'sql'                     => "varchar(20) NOT NULL default ''"
        ],
        'geox' =>
        [
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['geox'],
            'exclude'                 => true,
            'inputType'               => 'c4g_text',
            'eval'                    => ['mandatory'=>true, 'maxlength'=>20, 'tl_class'=>'w50 wizard'],
            'save_callback'           => [[$cbClass,'setLocLon']],
            'wizard'                  => [['con4gis\MapsBundle\Resources\contao\classes\GeoPicker', 'getPickerLink']],
            'sql'                     => "varchar(20) NOT NULL default ''"
        ],
        'geoy' =>
        [
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['geoy'],
            'exclude'                 => true,
            'inputType'               => 'c4g_text',
            'eval'                    => ['mandatory'=>true, 'maxlength'=>20, 'tl_class'=>'w50 wizard'],
            'save_callback'           => [[$cbClass,'setLocLat']],
            'wizard'                  => [['con4gis\MapsBundle\Resources\contao\classes\GeoPicker', 'getPickerLink']],
            'sql'                     => "varchar(20) NOT NULL default ''"
        ],
        'geoJson' =>
        [
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['geoJson'],
            'exclude'                 => true,
            'inputType'               => 'textarea',
            'eval'                    => ['tl_class'=>'wizard', 'preserve_tags'=>true],
            'wizard'                  => [['con4gis\EditorBundle\Classes\Contao\GeoEditor', 'getEditorLink']],
            'sql'                     => "text NULL"
        ],
    ],
];
