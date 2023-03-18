<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2022, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

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

use con4gis\DataBundle\Controller\MemberEditableController;
use con4gis\DataBundle\Controller\PublicNonEditableController;

$cbClass = \con4gis\DataBundle\Classes\Contao\Callbacks\ModuleCallback::class;

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'c4g_data_mode';

$GLOBALS['TL_DCA']['tl_module']['palettes'][PublicNonEditableController::TYPE] = '{title_legend},name,headline,type;{caption_legend},'.
    'captionPlural,caption;{c4g_data_type_legend},c4g_data_mode,showSelectFilter,selectFilterLabel,showDirectorySelectFilter,directorySelectFilterLabel,labelMode,showFilterResetButton,'.
    'filterResetButtonCaption;{c4g_expert_legend},hideDetails,showLabelsInList,phoneLabel,mobileLabel,faxLabel,emailLabel,websiteLabel,availableFieldsList,c4g_order_by_fields;{mapPage_legend},mapPage';

$GLOBALS['TL_DCA']['tl_module']['subpalettes']['c4g_data_mode_0'] = '';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['c4g_data_mode_1'] = 'c4g_data_type';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['c4g_data_mode_2'] = 'c4g_data_directory';

$GLOBALS['TL_DCA']['tl_module']['palettes'][MemberEditableController::TYPE] =
    '{title_legend},name,headline,type;{c4g_data_type_legend},c4g_data_type;{c4g_authorized_groups_legend},authorizedGroups;{c4g_expert_legend},availableFieldsList,availableFieldsListNonEditable,allowCreateRows,allowDeleteRows';

$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_data_mode'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_data_mode'],
        'exclude'                 => true,
        'default'                 => false,
        'inputType'               => 'radio',
        'options'                 => ['0', '1', '2'],
        'reference'               => &$GLOBALS['TL_LANG']['tl_module']['c4g_data_mode_option'],
        'sql'                     => "char(1) NOT NULL default '0'",
        'eval'                    => ['submitOnChange' => true]
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_data_type'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_data_type'],
        'exclude'                 => true,
        'inputType'               => 'select',
        'foreignKey'              => 'tl_c4g_data_type.name',
        'eval'                    => ['includeBlankOption' => true, 'multiple' => true, 'chosen' => true, 'tl_class' => 'clr'],
        'sql'                     => "text NULL"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_data_directory'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_data_directory'],
        'exclude'                 => true,
        'inputType'               => 'select',
        'foreignKey'              => 'tl_c4g_data_directory.name',
        'eval'                    => ['includeBlankOption' => true, 'multiple' => true, 'chosen' => true, 'tl_class' => 'clr'],
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
        'sql'                     => "varchar(200) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['captionPlural'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['captionPlural'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'sql'                     => "varchar(200) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['showSelectFilter'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['showSelectFilter'],
        'exclude'                 => true,
        'default'                 => false,
        'inputType'               => 'checkbox',
        'sql'                     => "char(1) NOT NULL default '0'"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['selectFilterLabel'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['selectFilterLabel'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'sql'                     => "varchar(100) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['showDirectorySelectFilter'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['showDirectorySelectFilter'],
        'exclude'                 => true,
        'default'                 => false,
        'inputType'               => 'checkbox',
        'sql'                     => "char(1) NOT NULL default '0'"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['directorySelectFilterLabel'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['directorySelectFilterLabel'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'sql'                     => "varchar(100) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['labelMode'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['labelMode'],
        'exclude'                 => true,
        'inputType'               => 'select',
        'options'                 => ['0', '1', '2'],
        'reference'               => &$GLOBALS['TL_LANG']['tl_module']['labelMode_option'],
        'sql'                     => "varchar(1) NOT NULL default ''"
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
        'sql'                     => "varchar(100) NOT NULL default ''"
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

$GLOBALS['TL_DCA']['tl_module']['fields']['phoneLabel'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['phoneLabel'],
        'exclude'                 => true,
        'default'                 => '',
        'inputType'               => 'text',
        'eval'                    => ['allowHtml' => true],
        'sql'                     => "varchar(100) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['mobileLabel'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['mobileLabel'],
        'exclude'                 => true,
        'default'                 => '',
        'inputType'               => 'text',
        'eval'                    => ['allowHtml' => true],
        'sql'                     => "varchar(100) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['faxLabel'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['faxLabel'],
        'exclude'                 => true,
        'default'                 => '',
        'inputType'               => 'text',
        'eval'                    => ['allowHtml' => true],
        'sql'                     => "varchar(100) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['emailLabel'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['emailLabel'],
        'exclude'                 => true,
        'default'                 => '',
        'inputType'               => 'text',
        'eval'                    => ['allowHtml' => true],
        'sql'                     => "varchar(100) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['websiteLabel'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['websiteLabel'],
        'exclude'                 => true,
        'default'                 => '',
        'inputType'               => 'text',
        'eval'                    => ['allowHtml' => true],
        'sql'                     => "varchar(100) NOT NULL default ''"
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
        'sql'                     => "text NOT NULL default ".$defaultAvailableFieldsList
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['availableFieldsListNonEditable'] =
    [
        'default'                 => serialize([]),
        'exclude'                 => true,
        'options_callback'        => [$cbClass, 'loadAvailableFieldsNonEditableOptions'],
        'inputType'               => 'checkbox',
        'eval'                    => [
            'class'               => 'clr',
            'multiple'            => true,
        ],
        'sql'                     => "text NOT NULL"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['allowCreateRows'] =
    [
        'exclude'                 => true,
        'inputType'               => 'checkbox',
        'eval'                    => [
            'class'               => 'clr'
        ],
        'sql'                     => "char(1) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['allowDeleteRows'] =
    [
        'exclude'                 => true,
        'inputType'               => 'checkbox',
        'sql'                     => "char(1) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_order_by_fields'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_order_by_fields'],
        'exclude'                 => true,
        'default'                 => serialize(['name']),
        'options_callback'        => [$cbClass, 'loadOrderByFieldsOptions'],
        'inputType'               => 'checkboxWizard',
        'eval'                    => [
            'class'               => 'clr',
            'multiple'            => true,
        ],
        'sql'                     => "text NOT NULL default ".serialize(['name'])
    ];

$GLOBALS['TL_DCA']['tl_module']['fields']['authorizedGroups'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['authorizedGroups'],
        'exclude'                 => true,
        'default'                 => [],
        'foreignKey'              => 'tl_member_group.name',
        'inputType'               => 'checkboxWizard',
        'eval'                    => [
            'class'               => 'clr',
            'multiple'            => true,
            'mandatory'           => true
        ],
        'sql'                     => "text NULL"
    ];

