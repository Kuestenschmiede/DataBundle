<?php
/**
 * Created by PhpStorm.
 * User: cro
 * Date: 19.04.2017
 * Time: 15:12
 */

namespace con4gis\DataBundle\Resources\contao\modules;

use con4gis\CoreBundle\Classes\C4GUtils;
use con4gis\CoreBundle\Classes\ResourceLoader;
use con4gis\CoreBundle\Resources\contao\models\C4gLogModel;
use con4gis\DataBundle\Resources\contao\models\DataCustomFieldModel;
use con4gis\DataBundle\Resources\contao\models\DataDirectoryModel;
use con4gis\DataBundle\Resources\contao\models\DataElementModel;
use con4gis\DataBundle\Resources\contao\models\DataTypeModel;
use con4gis\DataBundle\Resources\contao\models\PublicNonEditableModel;
use con4gis\ProjectsBundle\Classes\Buttons\C4GFilterButton;
use con4gis\ProjectsBundle\Classes\Buttons\C4GCheckboxFilterButton;
use con4gis\ProjectsBundle\Classes\Buttons\C4GFilterResetButton;
use con4gis\ProjectsBundle\Classes\Buttons\C4GSelectFilterButton;
use con4gis\ProjectsBundle\Classes\Database\C4GBrickDatabaseType;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GClassField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GDataClassField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GHeadlineField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GIconField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GImageField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GKeyField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GLinkButtonField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GLinkField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GMapLinkButtonField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GMultiLinkField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GTextField;
use con4gis\ProjectsBundle\Classes\Framework\C4GBrickModuleParent;
use con4gis\ProjectsBundle\Classes\Lists\C4GBrickRenderMode;
use con4gis\ProjectsBundle\Classes\Views\C4GBrickViewType;
use Contao\Database;
use Contao\StringUtil;


class PublicNonEditableModule extends C4GBrickModuleParent
{
    protected $viewType             = C4GBrickViewType::PUBLICVIEW;
    protected $languageFile         = 'tl_c4g_data_element';

    protected $modelClass           = PublicNonEditableModel::class;
    protected $modelListFunction    = 'find';

    protected $databaseType         = C4GBrickDatabaseType::DCA_MODEL;
    protected $tableName            = 'tl_c4g_data_element';

    protected $loadConditionalFieldDisplayResources = false;
    protected $loadTriggerSearchFromOtherModuleResources = true;
    protected $loadMoreButtonResources = true;
    protected $jQueryUseMaps = true;
    protected $jQueryUseMapsEditor = true;
    protected $loadCkEditor5Resources = true;
    protected $loadMultiColumnResources = true;
    protected $loadMiniSearchResources = true;
    protected $loadHistoryPushResources = true;

    public static $type = [];
    public static $directory = [];
    public static $showLabelsInList = false;
    public static $dataMode = 0;

    public function initBrickModule($id)
    {
        parent::initBrickModule($id);

        //Parameter zur Liste
        $this->listParams->setRenderMode(C4GBrickRenderMode::LISTBASED);
        $this->listParams->setWithExportButtons(false);
        $this->listParams->setDisplayLength(50);
        $this->listParams->setLengthChange(false);
        $this->listParams->setPaginate(true);

        $this->listParams->setWithDetails($this->hideDetails !== '1');
        $this->listParams->setShowFullTextSearchInHeadline();
        $this->listParams->setShowItemType();
        $this->dialogParams->setTabContent(false);
        $this->dialogParams->setWithLabels(false);
        $this->dialogParams->setWithDescriptions(false);
        $this->dialogParams->setId($id);

        $customFields = DataCustomFieldModel::findBy('frontendFilterList', 1);
        if ($customFields !== null) {
            foreach ($customFields as $customField) {
                if ($customField->published === '1') {
                    switch ($customField->type) {
                        case 'icon':
                            $this->listParams->addFilterButton(
                                new C4GFilterButton(
                                    $customField->icon,
                                    $customField->frontendName ?: $customField->name ?: '',
                                    $customField->alias
                                )
                            );
                            $alias = $customField->alias;
                            ResourceLoader::loadCssResourceTag(
                                '.filter_' . $alias . '_parent > div:not(.filter_' . $alias . '_child) {display: none;}'
                            );
                            break;
                        case 'checkbox':
                            $filterCheckbox = new C4GCheckboxFilterButton(
                                $customField->frontendName ?: $customField->name,
                                $customField->frontendName ?: $customField->name ?: '',
                                $customField->alias
                            );
                            $filterCheckbox->setStyle($customField->frontendFilterCheckboxStyling);
                            $filterCheckbox->setLabelChecked($customField->frontendFilterCheckboxButtonLabelOn);
                            $filterCheckbox->setLabelUnChecked($customField->frontendFilterCheckboxButtonLabelOff);
                            $this->listParams->addFilterButton(
                                $filterCheckbox
                            );
                            $alias = $customField->alias;
                            ResourceLoader::loadCssResourceTag(
                                '.filter_' . $alias . '_parent > div:not(.filter_' . $alias . '_child) {display: none;}'
                            );
                            break;
                        case 'link':
                            $this->listParams->addFilterButton(
                                new C4GFilterButton(
                                    $customField->linkTitle,
                                    $customField->frontendName ?: $customField->name ?: '',
                                    $customField->alias
                                )
                            );
                            $alias = $customField->alias;
                            ResourceLoader::loadCssResourceTag(
                                '.filter_' . $alias . '_parent > div:not(.filter_' . $alias . '_child) {display: none;}'
                            );
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        static::$type = StringUtil::deserialize($this->c4g_data_type);
        static::$directory = StringUtil::deserialize($this->c4g_data_directory);
        static::$showLabelsInList = $this->showLabelsInList === '1';
        static::$dataMode = $this->c4g_data_mode;

        if ((($this->c4g_data_mode === '1' && empty(static::$type)) || ($this->c4g_data_mode === '2' && empty(static::$directory))
            || ($this->c4g_data_mode === '0')) && $this->showSelectFilter) {
            $typeStmt = Database::getInstance()->prepare("SELECT DISTINCT tl_c4g_data_type.* FROM tl_c4g_data_type JOIN tl_c4g_data_element ON tl_c4g_data_element.type = tl_c4g_data_type.id where tl_c4g_data_type.allowPublishing != '1' OR tl_c4g_data_element.published = 1");
            $typeResult = $typeStmt->execute()->fetchAllAssoc();
            if (!empty($typeResult)) {
                $options = [];
                foreach ($typeResult as $row) {
                    if ($row['name'] !== '') {
                        $options[] = $row['name'];
                        ResourceLoader::loadCssResourceTag(
                            '.filter_type_' . str_replace([' ', '/', '.', ',', '-'], '', $row['name']) . '_parent > div:not(.filter_type_' . str_replace([' ', '/', '.', ',', '-'], '', $row['name']) . '_child) {display: none;}'
                        );
                    }
                }
                sort($options);
                $this->listParams->addFilterButton(new C4GSelectFilterButton($options, 'type', $this->selectFilterLabel ?: $GLOBALS['TL_LANG']['con4gis']['data']['frontend']['filter_by_category'], 'c4g_list_type_filter', intval($this->labelMode)));
            }
        } elseif ($this->c4g_data_mode === '1' && sizeof(static::$type) > 1 && $this->showSelectFilter) {
            $options = [];
            foreach (static::$type as $type) {
                $model = DataTypeModel::findByPk($type);
                if ($model !== null) {
                    $options[] = $model->name;
                    ResourceLoader::loadCssResourceTag(
                        '.filter_type_'.str_replace([' ', '/', '.', ',', '-'], '', $model->name).'_parent > div:not(.filter_type_'.str_replace([' ', '/', '.', ',', '-'], '', $model->name).'_child) {display: none;}'
                    );
                }
            }
            sort($options);
            $this->listParams->addFilterButton(new C4GSelectFilterButton($options, 'type', $this->selectFilterLabel ?: $GLOBALS['TL_LANG']['con4gis']['data']['frontend']['filter_by_category'], 'c4g_list_type_filter', intval($this->labelMode)));
        } elseif ($this->c4g_data_mode === '2' && sizeof(static::$directory) > 1) {
            $options = [];
            $typeOptions = [];
            foreach (static::$directory as $directory) {
                $directoryModel = DataDirectoryModel::findByPk($directory);
                if ($directoryModel !== null) {
                    if ($directoryModel->name !== '') {
                        $options[] = $directoryModel->name;
                        $typeOptions = array_merge($typeOptions, StringUtil::deserialize($directoryModel->types));
                        ResourceLoader::loadCssResourceTag(
                            '.filter_directory_' . str_replace([' ', '/', '.', ',', '-'], '', $directoryModel->name) . '_parent > div:not(.filter_directory_' . str_replace([' ', '/', '.', ',', '-'], '', $directoryModel->name) . '_child) {display: none;}'
                        );
                    }
                }
            }
            if ($this->showDirectorySelectFilter) {
                sort($options);
                $this->listParams->addFilterButton(new C4GSelectFilterButton($options, 'directory', $this->directorySelectFilterLabel ?: $GLOBALS['TL_LANG']['con4gis']['data']['frontend']['filter_by_directory'], 'c4g_list_directory_filter', intval($this->labelMode)));
            }
            $typeOptions = array_unique($typeOptions);
            if (!empty($typeOptions) && $this->showSelectFilter) {
                $options = [];
                foreach ($typeOptions as $option) {
                    $typeModel = DataTypeModel::findByPk($option);
                    if ($typeModel !== null) {
                        $options[] = $typeModel->name;
                        ResourceLoader::loadCssResourceTag(
                            '.filter_type_'.str_replace([' ', '/', '.', ',', '-'], '', $typeModel->name).'_parent > div:not(.filter_type_'.str_replace([' ', '/', '.', ',', '-'], '', $typeModel->name).'_child) {display: none;}'
                        );
                    }
                }
                sort($options);
                $this->listParams->addFilterButton(new C4GSelectFilterButton($options, 'type', $this->selectFilterLabel ?: $GLOBALS['TL_LANG']['con4gis']['data']['frontend']['filter_by_category'], 'c4g_list_type_filter', intval($this->labelMode)));

            }
        }

        if ($this->showFilterResetButton) {
            $this->listParams->addFilterButton(new C4GFilterResetButton($this->filterResetButtonCaption ?: '', $this->filterResetButtonCaption ?: ''));
        }

        if (strval($this->caption) !== '') {
            $this->dialogParams->setBrickCaption(strval($this->caption));
        }

        if (strval($this->captionPlural) !== '') {
            $this->dialogParams->setBrickCaptionPlural(strval($this->captionPlural));
        }

        if (strval($this->itemType) !== '') {
            $this->listParams->setItemType($this->itemType);
        }
    }

    protected function compileCss()
    {
        parent::compileCss();
        ResourceLoader::loadCssResource('bundles/con4gisdata/css/public_non_editable_css.css', '');
    }

    public function addFields()
    {
        $fieldList = [];

        $fieldList[] = C4GKeyField::create('id', '', '', false);

        // List

        $availableFieldsList = StringUtil::deserialize($this->availableFieldsList);
        if (is_array($availableFieldsList)) {
            $numberOfHeadlines = 0;
            foreach ($availableFieldsList as $availableField) {
                $customField = DataCustomFieldModel::findBy('alias', $availableField);
                if ($customField !== null) {
                    if ($customField->frontendList === '1') {
                        if ($customField->type !== 'legend') {
                            if ($customField->type === 'link') {
                                $link = C4GLinkButtonField::create($customField->alias);
                                $link->setButtonLabel($customField->linkTitle)
                                    ->setNewTab($customField->linkNewTab === '1')
                                    ->setTargetMode(C4GLinkButtonField::TARGET_MODE_URL)
                                    ->setTargetPageUrl($customField->linkHref)
                                    ->setConditional()
                                    ->setFormField(false);
                                $fieldList[] = $link;
                            } elseif ($customField->type === 'icon') {
                                $icon = C4GIconField::create($customField->alias);
                                $icon->setIcon($customField->icon)
                                    ->setConditional()
                                    ->setFormField(false)
                                    ->setDescription($customField->description)
                                    ->setTitle($customField->frontendName ?: $customField->name);
                                $fieldList[] = $icon;
                            } else {
                                $fieldList[] = C4GTextField::create($customField->alias,
                                    $customField->name,
                                    $customField->description,
                                    false, true, true, false
                                )->setShowIfEmpty(false)
                                    ->setEncodeHtmlEntities(false);
                            }
                        } else {
                            $numberOfHeadlines += 1;
                            $headline = C4GHeadlineField::create($availableField,
                                $customField->frontendName ?: $customField->name ?: '',
                                '', false,
                                true, false, false);
                            $headline->setNumber($numberOfHeadlines);
                            $fieldList[] = $headline;
                        }
                    }
                } else {
                    switch ($availableField) {
                        case 'name':
                            $fieldList[] = C4GTextField::create('name',
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['name'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['name'][1],
                                false, true, true, false)
                                ->setItemprop('name')
                                ->setWithoutLabel()
                                ->setEncodeHtmlEntities(false);
                            break;
                        case 'image':
                            $fieldList[] = C4GImageField::create('image',
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['image'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['image'][1],
                                false, true, true, false)
                                ->setLightBoxField('imageLightBox')
                                ->setItemprop('image');
                            break;
                        case 'address':
                            $fieldList[] = C4GTextField::create('address',
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['address'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['address'][1],
                                false, true, true, false)
                                ->setEncodeHtmlEntities(false)
                                ->setItemprop('address')
                                ->setItemType('http://schema.org/PostalAddress')
                                ->setEncodeHtmlEntities(false);
                            break;
                        case 'businessHours':
                            $fieldList[] = C4GTextField::create('businessHours',
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['businessHours'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['businessHours'][1],
                                false, true, true, false)
                                ->setSimpleTextWithoutEditing()
                                ->setEncodeHtmlEntities(false);
                            break;
                        case 'phone':
                            $fieldList[] = C4GLinkField::create('phone',
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['phone'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['phone'][1],
                                false, true, true, false)
                                ->setAddStrBeforeValue('Tel.: ')
                                ->setShowIfEmpty(false)
                                ->setLinkType(C4GLinkField::LINK_TYPE_PHONE)
                                ->setItemprop('telephone');
                            break;
                        case 'mobile':
                            $fieldList[] = C4GLinkField::create('mobile',
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['mobile'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['mobile'][1],
                                false, true, true, false)
                                ->setAddStrBeforeValue(!static::$showLabelsInList ? 'Mobil: ' : 'Mobil')
                                ->setShowIfEmpty(false)
                                ->setLinkType(C4GLinkField::LINK_TYPE_PHONE);
                            break;
                        case 'fax':
                            $fieldList[] = C4GTextField::create('fax',
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['fax'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['fax'][1],
                                false, true, true, false)
                                ->setAddStrBeforeValue(!static::$showLabelsInList ? 'Fax: ' : 'Fax')
                                ->setShowIfEmpty(false)
                                ->setEncodeHtmlEntities(false);
                            break;
                        case 'email':
                            $fieldList[] = C4GLinkField::create('email',
                                strval($GLOBALS['TL_LANG']['tl_c4g_data_element']['email'][0]),
                                strval($GLOBALS['TL_LANG']['tl_c4g_data_element']['email'][1]),
                                false, true, true, false)
                                ->setAddStrBeforeValue(!static::$showLabelsInList ? 'Email: ' : 'Email')
                                ->setShowIfEmpty(false)
                                ->setLinkType(C4GLinkField::LINK_TYPE_EMAIL)
                                ->setItemprop('email');
                            break;
                        case 'website':
                            $fieldList[] = C4GLinkField::create('website',
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['website'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['website'][1],
                                false, true, true, false)
                                ->setAddStrBeforeValue(!static::$showLabelsInList ? 'Website: ' : 'Website')
                                ->setShowIfEmpty(false)
                                ->setLinkType(C4GLinkField::LINK_TYPE_DEFAULT)
                                ->setNewTab()
                                ->setLabelField('websiteLabel');
                            break;
                        case 'linkWizard':
                            $fieldList[] = C4GMultiLinkField::create('linkWizard',
                                '', '', false,
                                true, true, false);
                            break;
                        case 'datePublished':
                            $fieldList[] = C4GTextField::create('datePublished',
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['datePublished'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['datePublished'][1], false,
                                true, true, false)
                                ->setEncodeHtmlEntities(false);
                            break;
                        case 'ownerGroupId':
                            $fieldList[] = C4GTextField::create('ownerGroupId',
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['ownerGroupId'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_data_element']['ownerGroupId'][1], false,
                                true, true, false)
                                ->setEncodeHtmlEntities(false);
                            break;
                        case 'data_legend':
                        case 'businessHours_legend':
                        case 'address_legend':
                        case 'contact_legend':
                        case 'description_legend':
                        case 'image_legend':
                        case 'location_legend':
                        case 'filter_legend':
                        case 'accessibility_legend':
                        case 'linkWizard_legend':
                        case 'osm_legend':
                        case 'publish_legend':
                        case 'published_legend':
                            $numberOfHeadlines += 1;
                            $headline = C4GHeadlineField::create($availableField,
                                $GLOBALS['TL_LANG']['tl_c4g_data_element'][$availableField],
                                '', false,
                                true, false, false);
                            $headline->setNumber($numberOfHeadlines);
                            $fieldList[] = $headline;
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        $fieldList[] = C4GTextField::create('searchInfo',
            '',
            '',
            false, true, true, false)
            ->setShowIfEmpty(false)
            ->setHidden();

        if ($this->mapPage) {
            $fieldList[] = C4GMapLinkButtonField::create('maplink')
                ->setTargetPageId($this->mapPage)
                ->setButtonLabel('zur Karte')
                ->setLongitudeColumn('geox')
                ->setLatitudeColumn('geoy')
                ->setnewTab();
        }

        // Details

        $fieldList[] = C4GHeadlineField::create('data',
            $GLOBALS['TL_LANG']['tl_c4g_data_element']['data_legend'],
            '',
            true, false, false, false);

        $fieldList[] = C4GTextField::create('type',
            $GLOBALS['TL_LANG']['tl_c4g_data_element']['type'][0],
            $GLOBALS['TL_LANG']['tl_c4g_data_element']['type'][1],
            true, false, true, false)
            ->setWithoutLabel();

        $fieldList[] = C4GTextField::create('name',
            $GLOBALS['TL_LANG']['tl_c4g_data_element']['name'][0],
            $GLOBALS['TL_LANG']['tl_c4g_data_element']['name'][1],
            true, false, true, false)
            ->setWithoutLabel();

        $fieldList[] = C4GTextField::create('description',
            $GLOBALS['TL_LANG']['tl_c4g_data_element']['description'][0],
            $GLOBALS['TL_LANG']['tl_c4g_data_element']['description'][1],
            true, false, true, false)
            ->setSimpleTextWithoutEditing();

        $availableFieldsDetails = [
            'image',
            'address_legend',
            'addressName',
            'addressStreet',
            'addressCity',
            'addressCountry',
            'businessHours_legend',
            'businessHours',
            'contact_legend',
            'phone',
            'mobile',
            'fax',
            'email',
            'website',
            'linkWizard_legend',
            'linkWizard'
        ];

        $customFields = DataCustomFieldModel::findAll();

        $elementModel = DataElementModel::findByPk($this->dialogParams->getId());
        if ($elementModel !== null) {
            $typeModel = DataTypeModel::findByPk($elementModel->type);
            if ($typeModel !== null) {
                $availableFields = StringUtil::deserialize($typeModel->availableFields);
                foreach ($availableFields as $availableField) {
                    if (!in_array($availableField, $availableFieldsDetails, true)) {
                        if ($customFields !== null) {
                            foreach ($customFields as $customField) {
                                if ($customField->alias === $availableField && ($customField->frontendDetails === '1' || $customField->type === 'legend')) {
                                    $availableFieldsDetails[] = $availableField;
                                }
                            }
                        }
                    }
                }
            }
        } elseif ($customFields !== null) {
            foreach ($customFields as $customField) {
                if ($customField->frontendDetails === '1' || $customField->type === 'legend') {
                    $availableFieldsDetails[] = $customField->alias;
                }
            }
        }

        $legend = null;
        foreach ($availableFieldsDetails as $field) {
            $model = DataCustomFieldModel::findBy('alias', $field);
            if ($model !== null && $model->published === '1' && $model->type === 'legend') {
                $legend = C4GHeadlineField::create(strval($model->alias),
                    strval($model->frontendName ?: $model->name),
                    '',
                    true, false, false, false);
                $fieldList[] = $legend;
            } elseif ($model === null && C4GUtils::endsWith($field, '_legend') === true) {
                $legend = C4GHeadlineField::create($field,
                    $GLOBALS['TL_LANG']['tl_c4g_data_element'][$field],
                    '',
                    true, false, false, false);
                $fieldList[] = $legend;
            } elseif ($model === null) {
                $brickField = null;
                switch ($field) {
                    case 'addressStreetNumber':
                    case 'addressZip':
                        break;
                    case 'businessHours':
                        $brickField = C4GTextField::create('businessHours',
                            $GLOBALS['TL_LANG']['tl_c4g_data_element']['businessHours'][0],
                            $GLOBALS['TL_LANG']['tl_c4g_data_element']['businessHours'][1],
                            true, false, true, false)
                            ->setSimpleTextWithoutEditing()
                            ->setEncodeHtmlEntities(false)
                            ->setShowIfEmpty(false);
                        break;
                    case 'phone':
                        $brickField = C4GLinkField::create('phone',
                            $GLOBALS['TL_LANG']['tl_c4g_data_element']['phone'][0],
                            $GLOBALS['TL_LANG']['tl_c4g_data_element']['phone'][1],
                            true, false, true, false)
                            ->setAddStrBeforeValue('Tel.: ')
                            ->setShowIfEmpty(false)
                            ->setLinkType(C4GLinkField::LINK_TYPE_PHONE);
                        break;
                    case 'mobile':
                        $brickField = C4GLinkField::create('mobile',
                            $GLOBALS['TL_LANG']['tl_c4g_data_element']['mobile'][0],
                            $GLOBALS['TL_LANG']['tl_c4g_data_element']['mobile'][1],
                            true, false, true, false)
                            ->setAddStrBeforeValue('Mobil: ')
                            ->setShowIfEmpty(false)
                            ->setLinkType(C4GLinkField::LINK_TYPE_PHONE);
                        break;
                    case 'fax':
                        $brickField = C4GTextField::create('fax',
                            $GLOBALS['TL_LANG']['tl_c4g_data_element']['fax'][0],
                            $GLOBALS['TL_LANG']['tl_c4g_data_element']['fax'][1],
                            true, false, true, false)
                            ->setAddStrBeforeValue('Fax: ')
                            ->setShowIfEmpty(false);
                        break;
                    case 'email':
                        $brickField = C4GLinkField::create('email',
                            $GLOBALS['TL_LANG']['tl_c4g_data_element']['email'][0],
                            $GLOBALS['TL_LANG']['tl_c4g_data_element']['email'][1],
                            true, false, true, false)
                            ->setAddStrBeforeValue('Email: ')
                            ->setShowIfEmpty(false)
                            ->setLinkType(C4GLinkField::LINK_TYPE_EMAIL);
                        break;
                    case 'website':
                        $brickField = C4GLinkField::create('website',
                            $GLOBALS['TL_LANG']['tl_c4g_data_element']['website'][0],
                            $GLOBALS['TL_LANG']['tl_c4g_data_element']['website'][1],
                            true, false, true, false)
                            ->setAddStrBeforeValue('Website: ')
                            ->setShowIfEmpty(false)
                            ->setLinkType(C4GLinkField::LINK_TYPE_DEFAULT)
                            ->setNewTab()
                            ->setLabelField('websiteLabel');
                        break;
                    case 'image':
                        $brickField = C4GImageField::create('image',
                            $GLOBALS['TL_LANG']['tl_c4g_data_element']['image'][0],
                            $GLOBALS['TL_LANG']['tl_c4g_data_element']['image'][1],
                            true, false, true, false)
                            ->setLightBoxField('imageLightBox');
                        break;
                    case 'linkWizard':
                        $brickField = C4GMultiLinkField::create('linkWizard',
                            '', '', true,
                            false, true, false);
                        break;
                    default:
                        try {
                            $brickField = C4GTextField::create($field,
                                $GLOBALS['TL_LANG']['tl_c4g_data_element'][$field][0],
                                $GLOBALS['TL_LANG']['tl_c4g_data_element'][$field][1],
                                true, false, true, false)
                                ->setShowIfEmpty(false);
                        } catch (\Throwable $throwable) {
                            C4gLogModel::addLogEntry(
                                'data',
                                "$field is an assigned but not published custom field".
                                " or a regular field without language entries."
                            );
                        }
                }
                if ($brickField !== null) {
                    $fieldList[] = $brickField;
                }
                if ($legend !== null && $brickField !== null) {
                    $legend->addAssociatedField($brickField);
                }
            } elseif ($model->published === '1') {
                switch ($model->type) {
                    case 'icon':
                        $brickField = C4GIconField::create($field,
                            strval($model->name),
                            strval($model->description),
                            true, false, true, false)
                            ->setShowIfEmpty(false)
                            ->setFormField(true)
                            ->setIcon(strval($model->icon))
                            ->setConditional();
                        $fieldList[] = $brickField;
                        if ($legend !== null && $brickField !== null) {
                            $legend->addAssociatedField($brickField);
                        }
                        break;
                    default:
                        $brickField = C4GTextField::create($field,
                            strval($model->name),
                            strval($model->description),
                            true, false, true, false)
                            ->setShowIfEmpty(false);
                        $fieldList[] = $brickField;
                        if ($legend !== null && $brickField !== null) {
                            $legend->addAssociatedField($brickField);
                        }
                        break;
                }
            }
        }

        if ($this->mapPage) {
            $fieldList[] = C4GMapLinkButtonField::create('maplinkDetails')
                ->setFormField(true)
                ->setTableColumn(false)
                ->setTargetPageId($this->mapPage)
                ->setButtonLabel('zur Karte')
                ->setLongitudeColumn('geox')
                ->setLatitudeColumn('geoy')
                ->setnewTab();
        }

        foreach ($customFields as $customField) {
            if ($customField->frontendFilterList === '1') {
                try {
                    $classField = new C4GClassField();
                    $classField->setFieldName($customField->alias)
                        ->setStyleClass('filter_'.$customField->alias.'_child')
                        ->setOptions(['1', 1]);
                    $fieldList[] = $classField;
                } catch (\Throwable $throwable) {
                    C4gLogModel::addLogEntry('projects', $throwable->getMessage());
                }
            }
        }

        try {
            $classField = new C4GDataClassField();
            $classField->setFieldName('type');
            $classField->setClassPrefix('filter_type_');
            $classField->setClassSuffix('_child');
            $fieldList[] = $classField;
        } catch (\Throwable $throwable) {
            C4gLogModel::addLogEntry('projects', $throwable->getMessage());
        }

        try {
            $classField = new C4GDataClassField();
            $classField->setFieldName('directory');
            $classField->setClassPrefix('filter_directory_');
            $classField->setClassSuffix('_child');
            $fieldList[] = $classField;
        } catch (\Throwable $throwable) {
            C4gLogModel::addLogEntry('projects', $throwable->getMessage());
        }

        return $fieldList;
    }
}