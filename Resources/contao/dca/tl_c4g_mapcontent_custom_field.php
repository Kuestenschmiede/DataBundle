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
use con4gis\CoreBundle\Classes\DCA\Fields\TextField;
use con4gis\CoreBundle\Classes\DCA\Fields\CheckboxField;
use con4gis\MapContentBundle\Classes\Contao\Callbacks\MapcontentCustomFieldCallback;

$strName = 'tl_c4g_mapcontent_custom_field';
$cbClass = MapcontentCustomFieldCallback::class;

$dca = new DCA($strName);
$list = $dca->list();
$list->sorting()->fields(['name']);
$list->sorting()->panelLayout('filter;sort,search,limit');
$list->label()->fields(['name', 'type']);
$list->addRegularOperations($dca);
$dca->palette()->default(
    '{data_legend},name,legend,description,type'
);
$generalFields = ';{filter_search_legend},filter,search'.
    ';{mandatory_default_legend},mandatory,default'.
    ';{positioning_legend},class,margin';
$dca->palette()->selector(['type'])
    ->subPalette('type', 'text', "$generalFields;{type_specific_legend},maxLength")
    ->subPalette('type', 'textarea', "$generalFields;{type_specific_legend},maxLength,rte");

$id = new IdField('id', $dca);

$tStamp = new NaturalField('tstamp', $dca);

$name = new TextField('name', $dca);
$name->filter()->search();
$name->eval()->class('w50')->mandatory();

$name = new TextField('legend', $dca);
$name->eval()->class('w50');

$name = new TextField('description', $dca);
$name->eval()->class('clr');

$type = new SelectField('type', $dca);
$type->default('')
    ->optionsCallback($cbClass, 'loadTypes')
    ->sql("varchar(20) NOT NULL default ''")
    ->filter()->search()
    ->eval()->mandatory()
    ->maxlength(20)
    ->class('clr')
    ->submitOnChange()
    ->includeBlankOption();

$filter = new CheckboxField('filter', $dca);
$search = new CheckboxField('search', $dca);

$mandatory = new CheckboxField('mandatory', $dca);
$mandatory->eval()
    ->class('w50 m12');

$default = new TextField('default', $dca);
$default->eval()
    ->class('w50');

$class = new SelectField('class', $dca);
$class->default('w50')
    ->optionsCallback($cbClass, 'loadClassOptions')
    ->sql("varchar(10) NOT NULL default 'w50'")
    ->eval()
        ->class('w50');

$mandatory = new CheckboxField('margin', $dca);
$mandatory->eval()
    ->class('w50 m12');

/** Type specific */

$maxLength = new NaturalField('maxLength', $dca);
$maxLength->default(255)
    ->sql("int(10) unsigned NOT NULL default '255'");

$rte = new CheckboxField('rte', $dca);
$rte->eval()->class('clr m12');