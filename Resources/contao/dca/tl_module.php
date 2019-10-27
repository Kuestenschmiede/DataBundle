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

$GLOBALS['TL_DCA']['tl_module']['palettes']['public_noneditable'] = '{title_legend},name,headline,type;{c4g_mapcontent_type_legend},c4g_mapcontent_type;{mapPage_legend},mapPage';
$GLOBALS['TL_DCA']['tl_module']['palettes']['public_editable'] = '{title_legend},name,headline,type;';

$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_mapcontent_type'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_mapcontent_type'],
        'inputType'               => 'select',
        'foreignKey'              => 'tl_c4g_mapcontent_type.name',
        'eval'                    => ['includeBlankOption' => true],
        'sql'                     => "int(10) unsigned NOT NULL default '0'"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['mapPage'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['mapPage'],
        'inputType'               => 'pageTree',
        'sql'                     => "int(10) unsigned NOT NULL default '0'"
    ];
