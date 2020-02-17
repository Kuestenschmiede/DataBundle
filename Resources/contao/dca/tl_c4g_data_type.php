<?php
/**
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package   	con4gis
 * @version    7
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
use con4gis\CoreBundle\Classes\DCA\Fields\SQLField;
use con4gis\CoreBundle\Classes\DCA\Fields\TextField;
use con4gis\CoreBundle\Classes\DCA\Fields\CheckboxField;
use con4gis\CoreBundle\Classes\DCA\Fields\MultiCheckboxField;
use con4gis\DataBundle\Classes\Contao\Callbacks\TypeCallback;

$strName = 'tl_c4g_data_type';
$cbClass = TypeCallback::class;

$dca = new DCA('tl_c4g_data_type');
$dca->config()->markAsCopy('name');
$list = $dca->list();
$list->sorting()->fields(['name', 'availableFields']);
$list->sorting()->panelLayout('filter;sort,search,limit');
$list->label()->fields(['name', 'availableFields'])
    ->labelCallback($cbClass, 'getLabel');
$list->addRegularOperations($dca);
$dca->palette()->default('{data_legend},name,locstyle,availableFields,showLabels,allowPublishing;{searchEngine_legend},itemType');

$id = new IdField('id', $dca);

$tStamp = new NaturalField('tstamp', $dca);

$name = new TextField('name', $dca);
$name->filter()
    ->search();
$name->eval()
    ->class('clr')
    ->mandatory();

$locStyle = new SelectField('locstyle', $dca);
$locStyle->default('')
        ->optionsCallback($cbClass, 'getLocstyles')
        ->sql("varchar(20) NOT NULL default ''")
        ->eval()->class('clr')
            ->submitOnChange();

$showLabels = new CheckboxField('showLabels', $dca);
$showLabels->default(false);

$categorySort = new NaturalField('categorySort', $dca);

$availableFields = new MultiCheckboxField('availableFields', $dca);
$availableFields->optionsCallback($cbClass, 'loadAvailableFieldsOptions')
    ->inputType('checkboxWizard');

$itemType = new SelectField('itemType', $dca);
$itemType->default('')
    ->optionsCallback($cbClass, 'loadItemTypeOptions')
    ->sql("varchar(100) NOT NULL default ''")
    ->eval()
        ->class('clr')
        ->includeBlankOption();

$importId = new SQLField("importId", $dca, "int(20) unsigned NOT NULL default '0'");

$allowPublishing = new CheckboxField('allowPublishing', $dca);

