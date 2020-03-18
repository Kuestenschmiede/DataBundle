<?php

$GLOBALS['TL_DCA']['tl_member_group']['palettes']['default'] .= ';{data_bundle_legend},numberElements,addressName,addressStreet,addressStreetNumber,addressZip,addressCity,addressState,addressCountry,phone,mobile,email';

$GLOBALS['TL_DCA']['tl_member_group']['fields']['numberElements'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['numberElements'],
        'exclude'                 => true,
        'search'                  => true,
        'inputType'               => 'text',
        'eval'                    => ['tl_class'=>'w50'],
        'sql'                     => "int(10) NOT NULL default '0'"
    ];

$GLOBALS['TL_DCA']['tl_member_group']['fields']['addressName'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['addressName'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50 clr'],
        'sql'                     => "varchar(255) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_member_group']['fields']['addressStreet'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['addressStreet'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
        'sql'                     => "varchar(255) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_member_group']['fields']['addressStreetNumber'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['addressStreetNumber'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => ['maxlength'=>25, 'tl_class'=>'w50'],
        'sql'                     => "varchar(25) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_member_group']['fields']['addressZip'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['addressZip'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => ['maxlength'=>10, 'tl_class'=>'w50'],
        'sql'                     => "varchar(10) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_member_group']['fields']['addressCity'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['addressCity'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
        'sql'                     => "varchar(255) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_member_group']['fields']['addressState'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['addressState'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
        'sql'                     => "varchar(255) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_member_group']['fields']['addressCountry'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['addressCountry'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
        'sql'                     => "varchar(255) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_member_group']['fields']['phone'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['phone'],
        'exclude'                 => true,
        'search'                  => true,
        'inputType'               => 'text',
        'eval'                    => ['maxlength'=>64, 'rgxp'=>'phone', 'decodeEntities'=>true, 'tl_class'=>'w50 clr'],
        'sql'                     => "varchar(64) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_member_group']['fields']['mobile'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['mobile'],
        'exclude'                 => true,
        'search'                  => true,
        'inputType'               => 'text',
        'eval'                    => ['maxlength'=>64, 'rgxp'=>'phone', 'decodeEntities'=>true, 'tl_class'=>'w50'],
        'sql'                     => "varchar(64) NOT NULL default ''"
    ];

$GLOBALS['TL_DCA']['tl_member_group']['fields']['email'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['email'],
        'exclude'                 => true,
        'search'                  => true,
        'inputType'               => 'text',
        'eval'                    => ['maxlength'=>255, 'rgxp'=>'email', 'decodeEntities'=>true, 'tl_class'=>'w50'],
        'sql'                     => "varchar(255) NOT NULL default ''"
    ];