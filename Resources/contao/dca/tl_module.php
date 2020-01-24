<?php
/**
 * con4gis - the gis-kit
 *
 * @version   php 7
 * @package   east_frisia
 * @author    contributors (see "authors.txt")
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2018
 * @link      https://www.kuestenschmiede.de
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['public_noneditable'] = '{title_legend},name,headline,type;{caption_legend},captionPlural,caption;{c4g_data_type_legend},c4g_data_type,c4g_data_directory,showSelectFilter;{mapPage_legend},mapPage';
$GLOBALS['TL_DCA']['tl_module']['palettes']['public_editable'] = '{title_legend},name,headline,type;';

$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_data_type'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_data_type'],
        'inputType'               => 'select',
        'foreignKey'              => 'tl_c4g_data_type.name',
        'eval'                    => ['includeBlankOption' => true],
        'sql'                     => "int(10) unsigned NOT NULL default '0'"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_data_directory'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_data_directory'],
        'inputType'               => 'select',
        'foreignKey'              => 'tl_c4g_data_directory.name',
        'eval'                    => ['includeBlankOption' => true],
        'sql'                     => "int(10) unsigned NOT NULL default '0'"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['mapPage'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['mapPage'],
        'inputType'               => 'pageTree',
        'sql'                     => "int(10) unsigned NOT NULL default '0'"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['caption'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['caption'],
        'inputType'               => 'text',
        'sql'                     => "varchar(255) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['captionPlural'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['captionPlural'],
        'inputType'               => 'text',
        'sql'                     => "varchar(255) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['showSelectFilter'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['showSelectFilter'],
        'default'                 => false,
        'inputType'               => 'checkbox',
        'sql'                     => "char(1) NOT NULL default '0'"
    ];
