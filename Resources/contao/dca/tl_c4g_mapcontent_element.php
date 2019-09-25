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
use con4gis\CoreBundle\Classes\DCA\Fields\MultiColumnField;
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

/** Fields for use in child bundles */

$businessHours = new MultiColumnField('businessHours', $dca);
$businessHours->sql('text NULL');

$dayFrom = new SelectField('dayFrom', $dca, $businessHours);
$dayFrom->optionsCallback($cbClass, 'getDay')
    ->reference('day_reference')
    ->eval()->includeBlankOption();
$dayTo = new SelectField('dayTo', $dca, $businessHours);
$dayTo->optionsCallback($cbClass, 'getDay')
    ->reference('day_reference')
    ->eval()->includeBlankOption();
$timeFrom = new TextField('timeFrom', $dca, $businessHours);
$timeFrom->eval()->regEx('time');
$timeTo = new TextField('timeTo', $dca, $businessHours);
$timeTo->eval()->regEx('time');

$addressName = new TextField('addressName', $dca);
$addressName->eval()->class('w50');

$addressStreet = new TextField('addressStreet', $dca);
$addressStreet->eval()->class('clr w50');

$addressNumber = new NaturalField('addressStreetNumber', $dca);
$addressNumber->eval()->class('w50');

$addressZip = new TextField('addressZip', $dca);
$addressZip->sql('char(5) NULL')
    ->eval()->maxlength(5)
        ->class('w50');

$addressCity = new TextField('addressCity', $dca);
$addressCity->eval()->class('w50');

$phone = new TextField('phone', $dca);
$phone->eval()->regEx('phone')
        ->class('w50');

$fax = new TextField('fax', $dca);
$fax->eval()->regEx('fax')
        ->class('w50');

$email = new TextField('email', $dca);
$email->eval()->regEx('email')
        ->class('w50');