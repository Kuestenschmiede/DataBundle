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
use con4gis\MapContentBundle\Classes\Contao\Callbacks\MapcontentLocationCallback;

$strName = 'tl_c4g_mapcontent_location';
$cbClass = MapcontentLocationCallback::class;

$dca = new DCA('tl_c4g_mapcontent_location');
$list = $dca->list();
$list->sorting()->fields(['name']);
$list->sorting()->panelLayout('filter;sort,search,limit');
$list->label()->fields(['name', 'loctype',  'geox', 'geoy'])
    ->labelCallback($cbClass, 'getLabel');
$list->addRegularOperations($dca);
$dca->palette()->default('{data_legend},name,loctype');
$dca->palette()->selector(['loctype']);
$dca->palette()->subPalette('loctype', 'point', 'geox,geoy');
$dca->palette()->subPalette('loctype', 'line', 'geoJson');
$dca->palette()->subPalette('loctype', 'circle', 'geoJson');
$dca->palette()->subPalette('loctype', 'polygon', 'geoJson');

$id = new IdField('id', $dca);
$tStamp = new NaturalField('tstamp', $dca);
$name = new TextField('name', $dca);
$name->eval()->class('clr')->mandatory();
$locType = new SelectField('loctype', $dca);
$locType->default('point')
        ->options(['point', 'circle', 'line', 'polygon'])
        ->reference('loctype_ref')
        ->sql("varchar(20) NOT NULL default ''")
        ->eval()->class('clr')
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

