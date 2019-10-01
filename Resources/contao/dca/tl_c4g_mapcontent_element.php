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
use con4gis\CoreBundle\Classes\DCA\Fields\ImageField;
use con4gis\MapContentBundle\Classes\Contao\Callbacks\MapcontentElementCallback;

$strName = 'tl_c4g_mapcontent_element';
$cbClass = MapcontentElementCallback::class;

$dca = new DCA($strName);
$list = $dca->list();
$list->sorting()->fields(['name']);
$list->sorting()->panelLayout('filter;sort,search,limit');
$list->label()->fields(['name', 'type'])
    ->labelCallback($cbClass, 'getLabel');
$list->addRegularOperations($dca);
$dca->palette()->default('{data_legend},name,type;')
    ->selector(['type', 'loctype'])
    ->subPalette("loctype", "point", "geox,geoy")
    ->subPalette("loctype", "circle", "geoJson")
    ->subPalette("loctype", "line", "geoJson")
    ->subPalette("loctype", "polygon", "geoJson");

$types = \con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel::findAll();
foreach ($types as $type) {
    $dca->palette()->subPalette("type", $type->id, ";{location_legend},loctype;");
}

$id = new IdField('id', $dca);

$tStamp = new NaturalField('tstamp', $dca);

$name = new TextField('name', $dca);
$name->eval()->class('clr')->mandatory();

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
    ->includeBlankOption()
    ->submitOnChange();

$locType = new SelectField('loctype', $dca);
$locType->default('point')
    ->options(['point', 'circle', 'line', 'polygon'])
    ->reference('loctype_ref')
    ->sql("varchar(20) NOT NULL default ''")
    ->eval()->class('clr')
    ->includeBlankOption()
    ->submitOnChange();

$geoX = new TextField('geox', $dca);
$geoX->inputType('c4g_text')
    ->sql("varchar(20) NOT NULL default ''")
    ->wizard('con4gis\MapsBundle\Resources\contao\classes\GeoPicker', 'getPickerLink')
    ->saveCallback($cbClass, 'setLocLon')
    ->eval()->mandatory()
    ->maxlength(20)
    ->class('w50 wizard');

$geoY = new TextField('geoy', $dca);
$geoY->inputType('c4g_text')
    ->sql("varchar(20) NOT NULL default ''")
    ->wizard('con4gis\MapsBundle\Resources\contao\classes\GeoPicker', 'getPickerLink')
    ->saveCallback($cbClass, 'setLocLat')
    ->eval()->mandatory()
    ->maxlength(20)
    ->class('w50 wizard');

$geoJson = new TextAreaField('geoJson', $dca);
$geoJson->wizard('con4gis\EditorBundle\Classes\Contao\GeoEditor', 'getEditorLink')
    ->eval()->class('wizard')
    ->preserveTags();

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

$email = new TextField('website', $dca);
$email->eval()->regEx('url')
        ->class('w50');

$description = new TextAreaField('description', $dca);
$description->eval()->class('clr')
    ->rte('tinyMCE');

$image = new ImageField('image', $dca);
$image->saveCallback($cbClass, 'changeFileBinToUuid');

$imageMaxHeight = new NaturalField('imageMaxHeight', $dca);
$imageMaxHeight->default('200')->sql("int(10) unsigned NOT NULL default '200'")
    ->eval()->maxlength(10)->class('w50');
$imageMaxWidth = new NaturalField('imageMaxWidth', $dca);
$imageMaxWidth->default('200')->sql("int(10) unsigned NOT NULL default '200'")
    ->eval()->maxlength(10)->class('w50');