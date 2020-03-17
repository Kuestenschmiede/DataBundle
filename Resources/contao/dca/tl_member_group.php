<?php

$GLOBALS['TL_DCA']['tl_member_group']['palettes']['default'] .= ';{data_bundle_legend},numberElements,mobile,email';

$GLOBALS['TL_DCA']['tl_member_group']['fields']['numberElements'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['numberElements'],
        'exclude'                 => true,
        'search'                  => true,
        'inputType'               => 'text',
        'eval'                    => ['tl_class'=>'w50'],
        'sql'                     => "int(10) NOT NULL default '0'"
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