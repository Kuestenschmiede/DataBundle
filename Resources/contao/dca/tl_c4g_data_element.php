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
use con4gis\CoreBundle\Classes\DCA\Fields\SQLField;
use con4gis\CoreBundle\Classes\DCA\Fields\TextAreaField;
use con4gis\CoreBundle\Classes\DCA\Fields\TextField;
use con4gis\CoreBundle\Classes\DCA\Fields\ImageField;
use con4gis\DataBundle\Classes\Contao\Callbacks\ElementCallback;
use con4gis\CoreBundle\Classes\DCA\Fields\CheckboxField;
use con4gis\CoreBundle\Classes\DCA\Fields\DigitField;
use con4gis\CoreBundle\Classes\DCA\Fields\MultiCheckboxField;
use con4gis\CoreBundle\Classes\DCA\Fields\DatePickerField;
use con4gis\DataBundle\Resources\contao\models\DataCustomFieldModel;
use Contao\StringUtil;
use con4gis\CoreBundle\Classes\C4GUtils;

$strName = 'tl_c4g_data_element';
$cbClass = ElementCallback::class;

$dca = new DCA($strName);
$dca->config()->onsubmitCallback(\con4gis\MapsBundle\Classes\Caches\C4GMapsAutomator::class, 'purgeLayerApiCache')
    ->markAsCopy('name');
$list = $dca->list();
$list->sorting()->fields(['name']);
$list->sorting()->panelLayout('filter;sort,search,limit');
$list->label()->fields(['name', 'type'])
    ->labelCallback($cbClass, 'getLabel');
$list->addRegularOperations($dca);
$dca->palette()->default('{data_legend},name,type')
    ->selector(['type', 'loctype'])
    ->subPalette("loctype", "point", "geox,geoy")
    ->subPalette("loctype", "circle", "geoJson")
    ->subPalette("loctype", "line", "geoJson")
    ->subPalette("loctype", "polygon", "geoJson");

$types = \con4gis\DataBundle\Resources\contao\models\DataTypeModel::findAll();
if ($types !== null) {
    foreach ($types as $type) {
        $dca->palette()->subPalette("type", $type->id, ",parentElement;{location_legend},loctype;{description_legend},description;");

        if ($type->availableFields !== null) {
            $availableFields = StringUtil::deserialize($type->availableFields);

            $fields = '';

            $database = Database::getInstance();
            $stmt = $database->prepare("SELECT DISTINCT alias FROM tl_c4g_data_custom_field");
            $result = $stmt->execute()->fetchAllAssoc();
            $aliases = [];
            foreach ($result as $r) {
                $aliases[] = $r['alias'];
            }

            foreach ($availableFields as $availableField) {
                if (in_array($availableField, $aliases) === true) {
                    $model = DataCustomFieldModel::findOneBy('alias', $availableField);
                    if ($model->type === 'legend') {
                        $fields .= ';{'.strval($model->name).'}';
                    } else {
                        $fields .= ','.$availableField;
                    }
                } else {
                    if (C4GUtils::endsWith($availableField, 'legend') === true) {
                        $fields .= ';{'.$availableField.'}';
                    } else {
                        $fields .= ','.$availableField;
                    }
                }

                if ($availableField === 'businessHours') {
                    $fields .= ',businessHoursAdditionalInfo';
                } elseif ($availableField === 'image') {
                    $fields .= ',imageMaxHeight,imageMaxWidth,imageLink,imageLightBox,';
                }
            }



            $dca->palette()->subPalette("type", $type->id, $fields);
        }

        if ($type->allowPublishing === '1') {
            $dca->palette()->subPalette('type', $type->id, ';{published_legend},ownerGroupId,datePublished,published');
        }
    }
}

$id = new IdField('id', $dca);

$tStamp = new NaturalField('tstamp', $dca);

$name = new TextField('name', $dca);
$name->filter()->search();
$name->eval()->class('clr')->mandatory();

$type = new SelectField('type', $dca);
$type->default('')
    ->optionsCallback($cbClass, 'loadTypes')
    ->sql("varchar(20) NOT NULL default ''")
    ->filter()->search()
        ->eval()->mandatory()
        ->maxlength(20)
        ->class('clr')
        ->submitOnChange()
        ->chosen();

$parent = new SelectField('parentElement', $dca);
$parent->optionsCallback($cbClass, 'loadParentOptions')
    ->sql("int(10) NOT NULL default 0")
    ->default('0')
    ->eval()
        ->class('clr')
        ->chosen()
        ->includeBlankOption();

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
    ->wizard('con4gis\MapsBundle\Classes\GeoPicker', 'getPickerLink')
    ->saveCallback($cbClass, 'setLocLon')
    ->eval()
    ->maxlength(20)
    ->class('w50 wizard');

$geoY = new TextField('geoy', $dca);
$geoY->inputType('c4g_text')
    ->sql("varchar(20) NOT NULL default ''")
    ->wizard('con4gis\MapsBundle\Classes\GeoPicker', 'getPickerLink')
    ->saveCallback($cbClass, 'setLocLat')
    ->eval()
    ->maxlength(20)
    ->class('w50 wizard');

$geoJson = new TextAreaField('geoJson', $dca);
$geoJson->wizard('con4gis\EditorBundle\Classes\Contao\GeoEditor', 'getEditorLink')
    ->eval()->class('wizard')
    ->preserveTags();

$description = new TextAreaField('description', $dca);
$description->eval()->class('clr')
    ->rte('tinyMCE');

$businessHours = new MultiColumnField('businessHours', $dca);
$businessHours->sql('text NULL')
    ->eval()
        ->class('clr');

$businessHoursAdditionalInfo = new TextAreaField('businessHoursAdditionalInfo', $dca);
$businessHoursAdditionalInfo->eval()->class('clr');

$dayFrom = new SelectField('dayFrom', $dca, $businessHours);
$dayFrom->optionsCallback($cbClass, 'getDay')
    ->reference('day_reference')
    ->default('')
    ->sql("char(1) NOT NULL default ''")
    ->eval()->includeBlankOption();
$dayTo = new SelectField('dayTo', $dca, $businessHours);
$dayTo->optionsCallback($cbClass, 'getDay')
    ->reference('day_reference')
    ->default('')
    ->sql("char(1) NOT NULL default ''")
    ->eval()->includeBlankOption();
$timeFrom = new TextField('timeFrom', $dca, $businessHours);
$timeFrom->eval()->regEx('time');
$timeTo = new TextField('timeTo', $dca, $businessHours);
$timeTo->eval()->regEx('time');

$addressName = new TextField('addressName', $dca);
$addressName->eval()->class('w50');

$addressStreet = new TextField('addressStreet', $dca);
$addressStreet->eval()->class('w50');

$addressNumber = new TextField('addressStreetNumber', $dca);
$addressNumber->sql("varchar(25) NOT NULL default ''")->eval()->class('w50');

$addressZip = new TextField('addressZip', $dca);
$addressZip->sql('varchar(10) NULL')
    ->eval()->maxlength(10)
        ->class('w50');

$addressCity = new TextField('addressCity', $dca);
$addressCity->eval()->class('w50');

$addressState = new TextField('addressState', $dca);
$addressState->eval()->class('w50');

$addressCountry = new TextField('addressCountry', $dca);
$addressCountry->eval()->class('w50');

$phone = new TextField('phone', $dca);
$phone->eval()->regEx('phone')
        ->class('w50');

$phone = new TextField('mobile', $dca);
$phone->eval()->regEx('phone')
        ->class('w50');

$fax = new TextField('fax', $dca);
$fax->eval()->regEx('fax')
        ->class('w50');

$email = new TextField('email', $dca);
$email->eval()->regEx('email')
        ->class('w50');

$website = new TextField('website', $dca);
$website->eval()->regEx('url')
        ->class('w50');

$websiteLabel = new TextField('websiteLabel', $dca);
$websiteLabel->eval()
        ->class('w50');

$image = new ImageField('image', $dca);
$image->saveCallback($cbClass, 'changeFileBinToUuid');

$imageMaxHeight = new NaturalField('imageMaxHeight', $dca);
$imageMaxHeight->default('200')->sql("int(10) unsigned NOT NULL default '200'")
    ->eval()->maxlength(10)->class('w50');
$imageMaxWidth = new NaturalField('imageMaxWidth', $dca);
$imageMaxWidth->default('200')->sql("int(10) unsigned NOT NULL default '200'")
    ->eval()->maxlength(10)->class('w50');

$imageLink = new TextField('imageLink', $dca);
$imageLink->eval()->class('clr');

$imageLightBox = new CheckboxField('imageLightBox', $dca);

$accessibility = new CheckboxField('accessibility', $dca);

$linkWizard = new MultiColumnField('linkWizard', $dca);
$linkWizard->sql('text NULL')
    ->eval()
    ->class('clr');

$linkTitle = new TextField('linkTitle', $dca, $linkWizard);
$linkTitle->eval()
    ->preserveTags()
    ->allowHtml();
$linkHref = new TextField('linkHref', $dca, $linkWizard);
$linkNewTab = new CheckboxField('linkNewTab', $dca, $linkWizard);
$osmId = new NaturalField('osmId', $dca);
$osmId->eval()->class('clr');

$publishFrom = new DatePickerField('publishFrom', $dca);
$publishFrom->saveCallback($cbClass, 'saveDate')
    ->loadCallback($cbClass, 'loadDate')
    ->sql("varchar(10) NOT NULL default ''");

$publishTo = new DatePickerField('publishTo', $dca);
$publishTo->saveCallback($cbClass, 'saveDate')
    ->loadCallback($cbClass, 'loadDate')
    ->sql("varchar(10) NOT NULL default ''");

$importId = new SQLField("importId", $dca, "int(20) unsigned NOT NULL default '0'");

$ownerGroupId = new SelectField('ownerGroupId', $dca);
$ownerGroupId->filter()->sql('int(10) NOT NULL default "0"')
    ->foreignKey('tl_member_group', 'name')
    ->eval()->includeBlankOption();
$published = new CheckboxField('published', $dca);
$datePublished = new DatePickerField('datePublished', $dca);
$datePublished->saveCallback($cbClass, 'saveDate')
    ->loadCallback($cbClass, 'loadDate')
    ->default(time());


/** Custom Fields */

$customFields = [];

foreach ($GLOBALS['con4gis']['data_custom_field_types'] as $type) {
    $customFields = DataCustomFieldModel::findBy('type', $type);

    if ($customFields !== null) {
        foreach ($customFields as $model) {
            if ($type === 'text') {
                $field = new TextField($model->alias, $dca);
                $field->hardLabel(strval($model->name), strval($model->description))
                    ->filter(boolval($model->filter))
                    ->search(boolval($model->search))
                    ->default(strval($model->defaultText))
                    ->sql(sprintf(
                        "varchar(%s) NOT NULL DEFAULT '%s'",
                        strval($model->maxLength),
                        strval($model->defaultText)))
                    ->eval()
                    ->mandatory(boolval($model->mandatory))
                    ->maxlength(intval($model->maxLength));
                $class = strval($model->class);
                if (boolval($model->margin) === true) {
                    $class .= ' m12';
                }
                $field->eval()->class($class);
            } elseif ($type === 'textarea') {
                $field = new TextAreaField($model->alias, $dca);
                $field->hardLabel(strval($model->name), strval($model->description))
                    ->filter(boolval($model->filter))
                    ->search(boolval($model->search))
                    ->default(strval($model->defaultTextArea))
                    ->sql(sprintf(
                        "TEXT NOT NULL DEFAULT '%s'",
                        strval($model->defaultTextArea)))
                    ->eval()
                        ->mandatory(boolval($model->mandatory))
                        ->maxlength(intval($model->maxLength));
                $class = strval($model->class);
                if (boolval($model->margin) === true) {
                    $class .= ' m12';
                }
                $field->eval()->class($class);
            } elseif ($type === 'texteditor') {
                $field = new TextAreaField($model->alias, $dca);
                $field->hardLabel(strval($model->name), strval($model->description))
                    ->filter(boolval($model->filter))
                    ->search(boolval($model->search))
                    ->default(strval($model->defaultTextEditor))
                    ->sql(sprintf(
                        "TEXT NULL DEFAULT '%s'",
                        strval($model->defaultTextEditor)))
                    ->eval()
                        ->mandatory(boolval($model->mandatory))
                        ->maxlength(intval($model->maxLength))
                        ->rte();
                $class = strval($model->class);
                if (boolval($model->margin) === true) {
                    $class .= ' m12';
                }
                $field->eval()->class($class);
            } elseif ($type === 'natural') {
                $field = new NaturalField($model->alias, $dca);
                $field->hardLabel(strval($model->name), strval($model->description))
                    ->filter(boolval($model->filter))
                    ->search(boolval($model->search))
                    ->default(strval($model->defaultNatural))
                    ->sql(sprintf(
                        "int(10) unsigned NOT NULL default '%s'",
                        strval($model->defaultNatural)))
                    ->eval()
                        ->mandatory(boolval($model->mandatory));
                $class = strval($model->class);
                if (boolval($model->margin) === true) {
                    $class .= ' m12';
                }
                $field->eval()->class($class);
            } elseif ($type === 'int') {
                $field = new DigitField($model->alias, $dca);
                $field->hardLabel(strval($model->name), strval($model->description))
                    ->filter(boolval($model->filter))
                    ->search(boolval($model->search))
                    ->default(strval($model->defaultInt))
                    ->sql(sprintf(
                        "int(10) signed NOT NULL default '%s'",
                        strval($model->defaultInt)))
                    ->eval()
                        ->mandatory(boolval($model->mandatory));
                $class = strval($model->class);
                if (boolval($model->margin) === true) {
                    $class .= ' m12';
                }
                $field->eval()->class($class);
            } elseif ($type === 'select') {
                $field = new SelectField($model->alias, $dca);
                $field->hardLabel(strval($model->name), strval($model->description))
                    ->filter(boolval($model->filter))
                    ->search(boolval($model->search))
                    ->default(strval($model->defaultSelect))
                    ->sql(sprintf(
                        "varchar(255) NOT NULL default '%s'",
                        strval($model->defaultSelect)))
                    ->eval()
                        ->mandatory(boolval($model->mandatory));
                $class = strval($model->class);
                if (boolval($model->margin) === true) {
                    $class .= ' m12';
                }
                $field->eval()->class($class);
                $options = StringUtil::deserialize($model->options);
                if ($options !== null) {
                    $formattedOptions = [];
                    foreach ($options as $option) {
                        $formattedOptions[$option['key']] = $option['value'];
                    }
                    $field->options($formattedOptions);
                }
            } elseif ($type === 'checkbox' || $type === 'link' || $type === 'icon') {
                $field = new CheckboxField($model->alias, $dca);
                $field->hardLabel(strval($model->name), strval($model->description))
                    ->filter(boolval($model->filter))
                    ->search(boolval($model->search))
                    ->default(strval($model->defaultCheckbox))
                    ->sql(sprintf(
                        "char(1) NOT NULL default '%s'",
                        strval($model->defaultCheckbox)))
                    ->eval()
                        ->mandatory(boolval($model->mandatory));
                $class = strval($model->class);
                if (boolval($model->margin) === true) {
                    $class .= ' m12';
                }
                $field->eval()->class($class);
            } elseif ($type === 'multicheckbox' || $type === 'filtermulticheckbox') {
                $field = new MultiCheckboxField($model->alias, $dca);
                $field->hardLabel(strval($model->name), strval($model->description))
                    ->filter(boolval($model->filter))
                    ->search(boolval($model->search))
                    ->default(StringUtil::deserialize($model->defaultMultiCheckbox))
                    ->eval()
                        ->mandatory(boolval($model->mandatory));
                $class = strval($model->class);
                if (boolval($model->margin) === true) {
                    $class .= ' m12';
                }
                $field->eval()->class($class);
                $options = StringUtil::deserialize($model->options);
                $formattedOptions = [];
                foreach ($options as $option) {
                    $formattedOptions[$option['key']] = $option['value'];
                }
                $field->options($formattedOptions);
            } elseif ($type === 'datepicker') {
                $field = new DatePickerField($model->alias, $dca);
                $field->hardLabel(strval($model->name), strval($model->description))
                    ->filter(boolval($model->filter))
                    ->search(boolval($model->search))
                    ->saveCallback($cbClass, 'saveDate')
                    ->loadCallback($cbClass, 'loadDate')
                    ->default(strval($model->defaultDatePicker))
                    ->sql(sprintf(
                        "varchar(10) NOT NULL default '%s'",
                        strval($model->defaultDatePicker)))
                    ->eval()
                        ->mandatory(boolval($model->mandatory));
                $class = strval($model->class);
                if (boolval($model->margin) === true) {
                    $class .= ' m12';
                }
                $field->eval()->class($class);
            } elseif ($type === 'foreignKey') {
                $field = new SelectField($model->alias, $dca);
                $field->hardLabel(strval($model->name), strval($model->description))
                    ->filter(boolval($model->filter))
                    ->search(boolval($model->search))
                    ->sql('int(10) NOT NULL default "0"')
                    ->foreignKey($model->foreignTable, $model->foreignField)
                    ->eval()
                        ->mandatory(boolval($model->mandatory));
                $class = strval($model->class);
                if (boolval($model->margin) === true) {
                    $class .= ' m12';
                }
                $field->eval()->class($class);
            }
        }
    }
}

