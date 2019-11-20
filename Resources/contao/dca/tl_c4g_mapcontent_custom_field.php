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
use con4gis\CoreBundle\Classes\DCA\Fields\TextAreaField;
use con4gis\CoreBundle\Classes\DCA\Fields\CheckboxField;
use con4gis\CoreBundle\Classes\DCA\Fields\DigitField;
use con4gis\CoreBundle\Classes\DCA\Fields\MultiColumnField;
use con4gis\CoreBundle\Classes\DCA\Fields\MultiCheckboxField;
use con4gis\CoreBundle\Classes\DCA\Fields\DatePickerField;
use con4gis\MapContentBundle\Classes\Contao\Callbacks\MapcontentCustomFieldCallback;

$strName = 'tl_c4g_mapcontent_custom_field';
$cbClass = MapcontentCustomFieldCallback::class;

$dca = new DCA($strName);
$list = $dca->list();
$list->sorting()->fields(['name']);
$list->sorting()->panelLayout('filter;sort,search,limit');
$list->label()->fields(['name', 'type'])->labelCallback($cbClass, 'getLabels');
$list->addRegularOperations($dca);
$dca->palette()->default(
    '{data_legend},name,alias,legend,description,type'
);
$generalFields = ';{filter_search_legend},filter,search'.
    ';{mandatory_legend},mandatory'.
    ';{positioning_legend},class,margin';
$dca->palette()->selector(['type'])
    ->subPalette('type', 'text', "$generalFields;{type_specific_legend},maxLength,defaultText")
    ->subPalette('type', 'textarea', "$generalFields;{type_specific_legend},maxLength,defaultTextArea")
    ->subPalette('type', 'texteditor', "$generalFields;{type_specific_legend},maxLength,defaultTextEditor")
    ->subPalette('type', 'natural', "$generalFields;{type_specific_legend},defaultNatural")
    ->subPalette('type', 'int', "$generalFields;{type_specific_legend},defaultInt")
    ->subPalette('type', 'select', "$generalFields;{type_specific_legend},options,defaultSelect")
    ->subPalette('type', 'checkbox', "$generalFields;{type_specific_legend},defaultCheckbox")
    ->subPalette('type', 'multicheckbox', "$generalFields;{type_specific_legend},options,defaultMultiCheckbox")
    ->subPalette('type', 'datepicker', "$generalFields;{type_specific_legend},defaultDatePicker");

$id = new IdField('id', $dca);

$tStamp = new NaturalField('tstamp', $dca);

$name = new TextField('name', $dca);
$name->filter()->search();
$name->eval()->class('w50')->mandatory();

$name = new TextField('alias', $dca);
$name->saveCallback($cbClass, 'saveAlias');
$name->eval()->class('w50');

$name = new TextField('legend', $dca);
$name->eval()->class('clr');

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

$defaultText = new TextField('defaultText', $dca);
$defaultText->label('default')
    ->eval()
        ->class('clr');

$defaultTextArea = new TextAreaField('defaultTextArea', $dca);
$defaultTextArea->label('default')
    ->eval()
        ->class('clr');

$defaultTextEditor = new TextAreaField('defaultTextEditor', $dca);
$defaultTextEditor->label('default')
    ->eval()
        ->class('clr')
        ->rte();

$defaultTextNatural = new NaturalField('defaultNatural', $dca);
$defaultTextNatural->label('default')
    ->eval()
        ->class('clr')
        ->regEx('natural');

$defaultInt = new DigitField('defaultInt', $dca);
$defaultInt->label('default')
    ->eval()
        ->class('clr')
        ->regEx('digit');

$options = new MultiColumnField('options', $dca);
$options->sql('text NULL');
$options->eval()
    ->class('clr');

$optionsKey = new TextField('key', $dca, $options);
$optionsValue = new TextField('value', $dca, $options);

$defaultSelect = new SelectField('defaultSelect', $dca);
$defaultSelect->optionsCallback($cbClass, 'loadDefaultoptions')
    ->sql("varchar(255) NOT NULL default ''")
    ->default('')
    ->label('default')
    ->eval()
        ->includeBlankOption()
        ->class('clr');

$defaultCheckbox = new CheckboxField('defaultCheckbox', $dca);
$defaultCheckbox->label('default')
    ->eval()
        ->class('clr');

$defaultSelect = new MultiCheckboxField('defaultMultiCheckbox', $dca);
$defaultSelect->optionsCallback($cbClass, 'loadDefaultoptions')
    ->label('default')
    ->eval()
        ->class('clr');

$defaultDatePicker = new DatePickerField('defaultDatePicker', $dca);
$defaultDatePicker->label('default');
