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


use con4gis\CoreBundle\Classes\DCA\DCA;
use con4gis\CoreBundle\Classes\DCA\Fields\IdField;
use con4gis\CoreBundle\Classes\DCA\Fields\NaturalField;
use con4gis\CoreBundle\Classes\DCA\Fields\SelectField;
use con4gis\CoreBundle\Classes\DCA\Fields\TextField;
use con4gis\MapContentBundle\Classes\Contao\Callbacks\MapcontentTypeCallback;

$strName = 'tl_c4g_mapcontent_type';
$cbClass = MapcontentTypeCallback::class;

$dca = new DCA('tl_c4g_mapcontent_type');
$list = $dca->list();
$list->sorting()->fields(['name', 'type', 'availableTags']);
$list->sorting()->panelLayout('filter;sort,search,limit');
$list->label()->fields(['name', 'type', 'availableTags'])
    ->labelCallback($cbClass, 'getLabel');
$list->addRegularOperations($dca);
$dca->palette()->default('{data_legend},name,locstyle,type,availableTags;');

$id = new IdField('id', $dca);
$tStamp = new NaturalField('tstamp', $dca);
$name = new TextField('name', $dca);
$name->eval()->class('clr')->mandatory();
$locStyle = new SelectField('locstyle', $dca);
$locStyle->default('')
        ->optionsCallback($cbClass, 'getLocstyles')
        ->sql("varchar(20) NOT NULL default ''")
        ->eval()->class('clr')
            ->submitOnChange();
$type = new SelectField('type', $dca);
$type->options($GLOBALS['con4gis']['mapcontent_types'])
    ->sql("varchar(20) NOT NULL default ''")
    ->eval()->mandatory()
        ->class('clr');
$availableTags = new SelectField('availableTags', $dca);
$availableTags->optionsCallback($cbClass, 'getAvailableTags')
    ->sql("blob NULL default ''")
    ->default('')
    ->eval()->mandatory()
        ->class('clr')
        ->chosen()
        ->multiple();
