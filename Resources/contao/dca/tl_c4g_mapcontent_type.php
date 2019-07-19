<?php
/**
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package   	con4gis
 * @version        6
 * @author  	    con4gis contributors (see "authors.txt")
 * @license 	    LGPL-3.0-or-later
 * @copyright 	Küstenschmiede GmbH Software & Design
 * @link              https://www.con4gis.org
 *
 */


use con4gis\MapContentBundle\Classes\Contao\Callbacks\MapcontentTypeCallback;

$strName = 'tl_c4g_mapcontent_type';
$cbClass = MapcontentTypeCallback::class;

/**
 * Table tl_c4g_mapcontent_type
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
            'fields' => ['name'],
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
        'default' => '{data_legend},name,locstyle,type,availableTags;'
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
        'locstyle' =>
        [
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['locstyle'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => [$cbClass, 'getLocstyles'],
            'eval'                    => ['tl_class'=>'clr', 'submitOnChange'=>true],
            'sql'                     => "varchar(20) NOT NULL default ''"
        ],
        'type' =>
        [
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['type'],
            'exclude'                 => true,
            'default'                 => '',
            'options'                 => $GLOBALS['con4gis']['mapcontent_types'],
            'inputType'               => 'select',
            'eval'                    => ['mandatory'=>true, 'tl_class'=>'clr'],
            'sql'                     => "varchar(20) NOT NULL default ''"
        ],
        'availableTags' =>
        [
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['availableTags'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'eval'                    => ['mandatory'=>true, 'tl_class'=>'clr', 'chosen' => true, 'multiple' => true],
            'options_callback'        => [$cbClass, 'getAvailableTags'],
            'sql'                     => "blob NULL default ''"
        ]
    ],
];
