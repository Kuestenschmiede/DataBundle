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
use con4gis\CoreBundle\Classes\DCA\Fields\SQLField;
use con4gis\DataBundle\Classes\Contao\Callbacks\CustomFieldCallback;
use \con4gis\CoreBundle\Classes\DCA\Operations\TogglePublishedOperation;

$strName = 'tl_c4g_data_custom_field';
$cbClass = CustomFieldCallback::class;

$dca = new DCA($strName);
$dca->config()->onloadCallback($cbClass, 'addHint')->markAsCopy('name');

$list = $dca->list();
$list->sorting()->fields(['name']);
$list->sorting()->panelLayout('filter;sort,search,limit');
$list->label()->fields(['name', 'type'])->labelCallback($cbClass, 'getLabels');
$list->addRegularOperations($dca);
new TogglePublishedOperation($dca, $cbClass, 'toggleIcon');
$dca->palette()->default(
    '{data_legend},name,alias,type'
);

$generalFields = ';{backend_legend},filter,search,mandatory,class,margin'.
    ';{frontend_legend},frontendName,frontendPopup,frontendList,frontendDetails,frontendFilter,frontendFilterList';

$generalFieldsWithoutPosition = ';{backend_legend},filter,search,mandatory'.
    ';{frontend_legend},frontendName,frontendPopup,frontendList,frontendDetails,frontendFilter,frontendFilterList';

$dca->palette()->selector(['type'])
    ->subPalette('type', 'text', ",description$generalFields;{type_specific_legend},maxLength,defaultText")
    ->subPalette('type', 'textarea', ",description$generalFields;{type_specific_legend},maxLength,defaultTextArea")
    ->subPalette('type', 'texteditor', ",description$generalFields;{type_specific_legend},maxLength,defaultTextEditor")
    ->subPalette('type', 'natural', ",description$generalFields;{type_specific_legend},defaultNatural")
    ->subPalette('type', 'int', ",description$generalFields;{type_specific_legend},defaultInt")
    ->subPalette('type', 'select', ",description$generalFields;{type_specific_legend},options,defaultSelect")
    ->subPalette('type', 'checkbox', ",description$generalFieldsWithoutPosition;{type_specific_legend},defaultCheckbox,".
        "frontendFilterCheckboxStyling,frontendFilterCheckboxButtonLabelOn,frontendFilterCheckboxButtonLabelOff")
    ->subPalette('type', 'icon', ",description$generalFields;{type_specific_legend},defaultCheckbox,icon")
    ->subPalette('type', 'multicheckbox', ",description$generalFields;{type_specific_legend},options,defaultMultiCheckbox")
    ->subPalette('type', 'datepicker', ",description$generalFields;{type_specific_legend},defaultDatePicker")
    ->subPalette('type', 'link', ",description$generalFields;{type_specific_legend},defaultCheckbox,linkTitle,linkHref,linkNewTab")
    ->subPalette('type', 'legend', ";{frontend_legend},frontendName,frontendPopup,frontendList,frontendDetails")
    ->subPalette('type', 'foreignKey', ",description$generalFields;{type_specific_legend},foreignTable,foreignField");

$id = new IdField('id', $dca);

$tStamp = new NaturalField('tstamp', $dca);

$published = new CheckboxField('published', $dca);

$name = new TextField('name', $dca);
$name->filter()->search();
$name->eval()->class('w50')->mandatory();

$alias = new TextField('alias', $dca);
$alias->saveCallback($cbClass, 'saveAlias');
$alias->eval()->class('w50')
    ->unique();

$description = new TextField('description', $dca);
$description->eval()->class('clr');

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
$filter->eval()
    ->class('w50');
$search = new CheckboxField('search', $dca);
$search->eval()
    ->class('w50');

$mandatory = new CheckboxField('mandatory', $dca);
$mandatory->eval()
    ->class('w50');

$class = new SelectField('class', $dca);
$class->default('w50')
    ->optionsCallback($cbClass, 'loadClassOptions')
    ->sql("varchar(10) NOT NULL default 'w50'")
    ->eval()
        ->class('w50');

$margin = new CheckboxField('margin', $dca);
$margin->eval()
    ->class('w50 m12');

$frontendName = new TextField('frontendName', $dca);
$frontendName->eval()->class('clr');

$frontendPopup = new CheckboxField('frontendPopup', $dca);
$frontendPopup->default(true)
    ->eval()
        ->class('clr w50');
$frontendList = new CheckboxField('frontendList', $dca);
$frontendList->default(true)
    ->eval()
        ->class('w50');
$frontendDetails = new CheckboxField('frontendDetails', $dca);
$frontendDetails->default(true)
    ->eval()
        ->class('w50 clr');

$frontendFilter = new CheckboxField('frontendFilter', $dca);
$frontendFilter->default(false)
    ->eval()
        ->class('w50');

$frontendFilterList = new CheckboxField('frontendFilterList', $dca);
$frontendFilterList->default(false)
    ->eval()
        ->class('w50 clr');

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

$defaultMultiCheckbox = new MultiCheckboxField('defaultMultiCheckbox', $dca);
$defaultMultiCheckbox->optionsCallback($cbClass, 'loadDefaultoptions')
    ->label('default')
    ->eval()
        ->class('clr');

$defaultDatePicker = new DatePickerField('defaultDatePicker', $dca);
$defaultDatePicker->label('default')
    ->saveCallback($cbClass, 'saveDate')
    ->loadCallback($cbClass, 'loadDate')
    ->sql("varchar(10) NOT NULL default ''");

$linkTitle = new TextField('linkTitle', $dca);
$linkTitle->eval()
    ->class('w50')
    ->preserveTags()
    ->allowHtml();

$linkHref = new TextField('linkHref', $dca);
$linkHref->eval()
    ->class('w50');

$linkNewTab = new CheckboxField('linkNewTab', $dca);
$linkNewTab->eval()
    ->class('clr');

$icon = new TextField('icon', $dca);
$icon->eval()
    ->allowHtml()
    ->mandatory()
    ->class('clr');

$frontendFilterCheckboxStyling = new SelectField('frontendFilterCheckboxStyling', $dca);
$frontendFilterCheckboxStyling->optionsCallback($cbClass, 'loadFrontendFilterCheckboxStylingOptions')
    ->sql("varchar(255) NOT NULL default ''")
    ->default('')
    ->eval()
        ->includeBlankOption()
        ->class('clr');

$icon = new TextField('frontendFilterCheckboxButtonLabelOn', $dca);
$icon->eval()
    ->allowHtml()
    ->class('w50');

$icon = new TextField('frontendFilterCheckboxButtonLabelOff', $dca);
$icon->eval()
    ->allowHtml()
    ->class('w50');

$foreignTable = new TextField('foreignTable', $dca);
$foreignTable->eval()
    ->allowHtml()
    ->class('w50');

$foreignField = new TextField('foreignField', $dca);
$foreignField->eval()
    ->allowHtml()
    ->class('w50');

$importId = new SQLField("importId", $dca, "int(20) unsigned NOT NULL default '0'");

