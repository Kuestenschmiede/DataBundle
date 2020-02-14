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

$cbClass = \con4gis\DataBundle\Classes\Contao\Callbacks\ModuleCallback::class;

$GLOBALS['TL_DCA']['tl_module']['palettes']['public_noneditable'] = '{title_legend},name,headline,type;{caption_legend},'.
    'captionPlural,caption;{c4g_data_type_legend},c4g_data_type,c4g_data_directory,showSelectFilter,showFilterResetButton,'.
    'filterResetButtonCaption;{c4g_expert_legend},hideDetails,showLabelsInList,availableFieldsList;{mapPage_legend},mapPage';


$GLOBALS['TL_DCA']['tl_module']['palettes']['member_editable'] = '{title_legend},name,headline,type;{c4g_expert_legend},availableFieldsList;';

$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_data_type'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_data_type'],
        'exclude'                 => true,
        'inputType'               => 'select',
        'foreignKey'              => 'tl_c4g_data_type.name',
        'eval'                    => ['includeBlankOption' => true, 'multiple' => true, 'chosen' => true, 'class' => 'clr'],
        'sql'                     => "text NULL"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_data_directory'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_data_directory'],
        'exclude'                 => true,
        'inputType'               => 'select',
        'foreignKey'              => 'tl_c4g_data_directory.name',
        'eval'                    => ['includeBlankOption' => true, 'multiple' => true, 'chosen' => true, 'class' => 'clr'],
        'sql'                     => "text NULL"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['mapPage'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['mapPage'],
        'exclude'                 => true,
        'inputType'               => 'pageTree',
        'sql'                     => "int(10) unsigned NOT NULL default '0'"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['caption'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['caption'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'sql'                     => "varchar(255) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['captionPlural'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['captionPlural'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'sql'                     => "varchar(255) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['showSelectFilter'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['showSelectFilter'],
        'exclude'                 => true,
        'default'                 => false,
        'inputType'               => 'checkbox',
        'sql'                     => "char(1) NOT NULL default '0'"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['showFilterResetButton'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['showFilterResetButton'],
        'exclude'                 => true,
        'default'                 => false,
        'inputType'               => 'checkbox',
        'sql'                     => "char(1) NOT NULL default '0'"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['filterResetButtonCaption'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['filterResetButtonCaption'],
        'exclude'                 => true,
        'default'                 => '',
        'inputType'               => 'text',
        'sql'                     => "varchar(255) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['hideDetails'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hideDetails'],
        'exclude'                 => true,
        'default'                 => false,
        'inputType'               => 'checkbox',
        'sql'                     => "char(1) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['showLabelsInList'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['showLabelsInList'],
        'exclude'                 => true,
        'default'                 => false,
        'inputType'               => 'checkbox',
        'sql'                     => "char(1) NOT NULL default ''"
    ];

$defaultAvailableFieldsList = serialize(
    [
        'name',
        'image',
        'address',
        'businessHours',
        'phone',
        'mobile',
        'fax',
        'email',
        'website',
        'linkWizard'
    ]);

$GLOBALS['TL_DCA']['tl_module']['fields']['availableFieldsList'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['availableFieldsList'],
        'exclude'                 => true,
        'default'                 => $defaultAvailableFieldsList,
        'options_callback'        => [$cbClass, 'loadAvailableFieldsOptions'],
        'inputType'               => 'checkboxWizard',
        'eval'                    => [
            'class'               => 'clr',
            'multiple'            => true,
        ],
        'sql'                     => "text NOT NULL default '$defaultAvailableFieldsList'"
    ];

