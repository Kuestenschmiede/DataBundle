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

use con4gis\CoreBundle\Classes\DCA\DCA;
use con4gis\CoreBundle\Classes\DCA\Fields\IdField;
use con4gis\CoreBundle\Classes\DCA\Fields\NaturalField;
use con4gis\CoreBundle\Classes\DCA\Fields\SelectField;
use con4gis\CoreBundle\Classes\DCA\Fields\TextAreaField;
use con4gis\CoreBundle\Classes\DCA\Fields\TextField;
use con4gis\MapContentBundle\Classes\Contao\Callbacks\MapcontentElementCallback;

$strName = 'tl_c4g_mapcontent_element';
$cbClass = MapcontentElementCallback::class;

$dca = new DCA($strName);
$list = $dca->list();
$list->sorting()->fields(['name']);
$list->sorting()->panelLayout('filter;sort,search,limit');
$list->label()->fields(['name', 'location', 'type'])
    ->labelCallback($cbClass, 'getLabel');
$list->addRegularOperations($dca);
$dca->palette()->default('{data_legend},name,description,location,tags,type;')
    ->selector(['type']);

$id = new IdField('id', $dca);

$tStamp = new NaturalField('tstamp', $dca);

$name = new TextField('name', $dca);
$name->eval()->class('clr')->mandatory();

$description = new TextAreaField('description', $dca);
$description->eval()->class('clr');

$location = new SelectField('location', $dca);
$location->default('')
    ->optionsCallback($cbClass, 'loadLocations')
    ->sql("varchar(20) NOT NULL default ''")
    ->eval()->class('clr')
        ->mandatory();

$tags = new SelectField('tags', $dca);
$tags->optionsCallback($cbClass, 'loadAvailableTags')
    ->sql("varchar(20) NOT NULL default ''")
    ->eval()->class('clr')
        ->chosen()
        ->multiple();

$type = new SelectField('type', $dca);
$type->default('')
    ->optionsCallback($cbClass, 'loadTypes')
    ->sql("varchar(20) NOT NULL default ''")
    ->eval()->mandatory()
    ->maxlength(20)
    ->class('clr')
    ->submitOnChange();
