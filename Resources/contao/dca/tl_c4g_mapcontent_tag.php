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

$strName = 'tl_c4g_mapcontent_tag';
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
        'default' => '{data_legend},name;'
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
        ]
    ],
];
