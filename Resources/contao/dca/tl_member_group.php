<?php

$GLOBALS['TL_DCA']['tl_member_group']['palettes']['default'] .= ';{data_bundle_legend},numberElements';

$GLOBALS['TL_DCA']['tl_member_group']['fields']['numberElements'] =
    [
        'label'                   => &$GLOBALS['TL_LANG']['tl_member_group']['numberElements'],
        'exclude'                 => true,
        'search'                  => true,
        'inputType'               => 'text',
        'eval'                    => ['tl_class'=>'w50'],
        'sql'                     => "int(10) NOT NULL default '0'"
    ];