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

use con4gis\MapContentBundle\Classes\Contao\Callbacks\MapcontentElementCallback;

$strName = 'tl_c4g_mapcontent_element';
$cbClass = MapcontentElementCallback::class;

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
            'fields' => ['name', 'location', 'type'],
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
        'default' => '{data_legend},name,description,location,type,tags;'
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
        
        'description' =>
        [
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['description'],
            'exclude'                 => true,
            'inputType'               => 'textarea',
            'eval'                    => ['tl_class'=>'clr'],
            'sql'                     => "text NULL"
        ],
        
        'location' =>
        [
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['location'],
            'default'                 => '',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => [$cbClass, 'loadLocations'],
            'eval'                    => ['tl_class'=>'clr'],
            'sql'                     => "varchar(20) NOT NULL default ''"
        ],
        
        'type' =>
        [
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['type'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'default'                 => '',
            'options_callback'        => [$cbClass, 'loadTypes'],
            'eval'                    => ['mandatory'=>true, 'maxlength'=>20, 'tl_class'=>'clr', 'submitOnChange' => true, 'includeBlankOption' => true],
            'sql'                     => "varchar(20) NOT NULL default ''"
        ],
        
        'tags' =>
        [
            'label'                   => &$GLOBALS['TL_LANG'][$strName]['tags'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => [$cbClass, 'loadAvailableTags'],
            'eval'                    => ['mandatory'=>false, 'tl_class'=>'clr', 'chosen' => true, 'multiple' => true],
            'sql'                     => "varchar(20) NOT NULL default ''"
        ],
        
    ],
];
