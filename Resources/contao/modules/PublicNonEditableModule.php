<?php
/**
 * Created by PhpStorm.
 * User: cro
 * Date: 19.04.2017
 * Time: 15:12
 */

namespace con4gis\MapContentBundle\Resources\contao\modules;

use con4gis\CoreBundle\Resources\contao\classes\C4GUtils;
use con4gis\CoreBundle\Resources\contao\classes\ResourceLoader;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentCustomFieldModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentElementModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use con4gis\MapContentBundle\Resources\contao\models\PublicNonEditableModel;
use con4gis\ProjectsBundle\Classes\Database\C4GBrickDatabaseType;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GHeadlineField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GImageField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GKeyField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GLinkField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GMapLinkButtonField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GMultiCheckboxField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GTextField;
use con4gis\ProjectsBundle\Classes\Framework\C4GBrickModuleParent;
use con4gis\ProjectsBundle\Classes\Lists\C4GBrickRenderMode;
use con4gis\ProjectsBundle\Classes\Views\C4GBrickViewType;
use Contao\StringUtil;


class PublicNonEditableModule extends C4GBrickModuleParent
{
    protected $viewType             = C4GBrickViewType::PUBLICVIEW;
    protected $languageFile         = 'tl_c4g_mapcontent_element';

    protected $modelClass           = PublicNonEditableModel::class;
    protected $modelListFunction    = 'find';

    protected $databaseType         = C4GBrickDatabaseType::DCA_MODEL;
    protected $tableName            = 'tl_c4g_mapcontent_element';

    protected $loadConditionalFieldDisplayResources = false;
    protected $loadTriggerSearchFromOtherModuleResources = true;
    protected $loadMoreButtonResources = true;
    protected $jQueryUseMaps = true;
    protected $jQueryUseMapsEditor = true;
    protected $loadCkEditor5Resources = true;
    protected $loadMultiColumnResources = true;
    protected $loadMiniSearchResources = true;

    public static $type = 0;

    public function initBrickModule($id)
    {
        parent::initBrickModule($id);

        //Parameter zur Liste
        $this->listParams->setRenderMode(C4GBrickRenderMode::LISTBASED);
        $this->listParams->setWithExportButtons(false);
        $this->listParams->setDisplayLength(50);
        $this->listParams->setLengthChange(false);
        $this->listParams->setPaginate(true);

        $this->listParams->setWithDetails(true);
        $this->listParams->setShowFullTextSearchInHeadline();
        $this->dialogParams->setTabContent(false);
        $this->dialogParams->setWithLabels(false);
        $this->dialogParams->setWithDescriptions(false);
        $this->dialogParams->setId($id);
        static::$type = $this->c4g_mapcontent_type;
    }

    protected function compileCss()
    {
        parent::compileCss();
        ResourceLoader::loadCssResource('bundles/con4gismapcontent/css/public_non_editable_css.css', '');
    }

    public function addFields()
    {
        $fieldList = [];

        $fieldList[] = C4GKeyField::create('id', '', '', false);

        // List

        $fieldList[] = C4GTextField::create('name',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['name'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['name'][1],
            false, true, true, false)
            ->setWithoutLabel();

        $fieldList[] = C4GImageField::create('image',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['image'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['image'][1],
            false, true, true, false);

        $fieldList[] = C4GTextField::create('addressName',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressName'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressName'][1],
            false, true, true, false)
            ->setShowIfEmpty(false)
            ->setSimpleTextWithoutEditing();

        $fieldList[] = C4GTextField::create('addressStreet',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressStreet'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressStreet'][1],
            false, true, true, false);

        $fieldList[] = C4GTextField::create('addressCity',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressCity'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressCity'][1],
            false, true, true, false);

        $fieldList[] = C4GTextField::create('businessHours',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['businessHours'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['businessHours'][1],
            false, true, true, false)
            ->setSimpleTextWithoutEditing()
            ->setEncodeHtmlEntities(false);

        $fieldList[] = C4GLinkField::create('phone',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['phone'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['phone'][1],
            false, true, true, false)
            ->setAddStrBeforeValue('Tel.: ')
            ->setShowIfEmpty(false)
            ->setLinkType(C4GLinkField::LINK_TYPE_PHONE);

        $fieldList[] = C4GLinkField::create('mobile',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['mobile'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['mobile'][1],
            false, true, true, false)
            ->setAddStrBeforeValue('Mobil: ')
            ->setShowIfEmpty(false)
            ->setLinkType(C4GLinkField::LINK_TYPE_PHONE);

        $fieldList[] = C4GTextField::create('fax',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['fax'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['fax'][1],
            false, true, true, false)
            ->setAddStrBeforeValue('Fax: ')
            ->setShowIfEmpty(false);

        $fieldList[] = C4GLinkField::create('email',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['email'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['email'][1],
            false, true, true, false)
            ->setAddStrBeforeValue('Email: ')
            ->setShowIfEmpty(false)
            ->setLinkType(C4GLinkField::LINK_TYPE_EMAIL);

        $fieldList[] = C4GLinkField::create('website',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['website'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['website'][1],
            false, true, true, false)
            ->setAddStrBeforeValue('Website: ')
            ->setShowIfEmpty(false)
            ->setLinkType(C4GLinkField::LINK_TYPE_DEFAULT)
            ->setNewTab();

        $customFields = MapcontentCustomFieldModel::findAll();
        foreach ($customFields as $customField) {
            if ($customField->frontendList === '1') {
                $fieldList[] = C4GTextField::create($customField->alias,
                    $customField->name,
                    $customField->description,
                    false, true, true, false
                );
            }
        }

        $fieldList[] = C4GMapLinkButtonField::create('maplink')
            ->setTargetPageId($this->mapPage)
            ->setButtonLabel('zur Karte')
            ->setLongitudeColumn('geox')
            ->setLatitudeColumn('geoy')
            ->setnewTab();

        $fieldList[] = C4GTextField::create('searchInfo',
            '',
            '',
            false, true, true, false)
            ->setShowIfEmpty(false)
            ->setHidden();

        // Details

        $fieldList[] = C4GHeadlineField::create('data',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['data_legend'],
            '',
            true, false, false, false);

        $fieldList[] = C4GTextField::create('name',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['name'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['name'][1],
            true, false, true, false)
            ->setWithoutLabel();

        $fieldList[] = C4GTextField::create('description',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['description'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['description'][1],
            true, false, true, false)
            ->setSimpleTextWithoutEditing();

        $model = MapcontentElementModel::findByPk($this->dialogParams->getId());
        $typeModel = MapcontentTypeModel::findByPk($model->type);
        $availableFields = $typeModel->availableFields;
        $availableFields = StringUtil::deserialize($availableFields);

        foreach ($availableFields as $fieldKey => $availableField) {
            $model = MapcontentCustomFieldModel::findBy('alias', $availableField);
            if ($model !== null) {
                if ($model->frontendDetails === '1') {
                    if ($model->type === 'legend') {
                        $i = $fieldKey + 1;
                        while ($i < count($availableFields)) {
                            $legendModel = MapcontentCustomFieldModel::findBy('alias', $availableFields[$i]);
                            if ($legendModel !== null && $legendModel->type === 'legend') {
                                break;
                            } if (C4GUtils::endsWith($availableFields[$i], '_legend') === true) {
                                break;
                            }
                            $i += 1;
                        }
                        $n = $fieldKey + 1;
                        if ($i > $n) {
                            $fieldList[] = C4GHeadlineField::create($model->alias,
                                $model->name,
                                $model->description,
                                true, false, false, false);
                        }
                    } elseif ($model->type === 'multicheckbox') {
                        $options = StringUtil::deserialize($model->options);
                        $formattedOptions = [];
                        foreach ($options as $option) {
                            $formattedOptions[] = [
                                'id' => $option['key'],
                                'name' => $option['value']
                            ];
                        }

                        $fieldList[] = C4GMultiCheckboxField::create($model->alias,
                            $model->name,
                            $model->description,
                            true, false, true, false)
                            ->setShowAsCsv()
                            ->setShowIfEmpty(false)
                            ->setOptions($formattedOptions)
                            ->setSort(false);
                    } else {
                        switch ($availableField) {
                            default:
                                $fieldList[] = C4GTextField::create($availableField,
                                    $model->name,
                                    $model->description,
                                    true, false, true, false);
                        }
                    }
                }
            } else {
                if (C4GUtils::endsWith($availableField, '_legend') === true) {
                    $i = $fieldKey + 1;
                    while ($i < count($availableFields)) {
                        $legendModel = MapcontentCustomFieldModel::findBy('alias', $availableFields[$i]);
                        if ($legendModel !== null && $legendModel->type === 'legend') {
                            break;
                        } if (C4GUtils::endsWith($availableFields[$i], '_legend') === true) {
                            break;
                        }
                        $i += 1;
                    }
                    if (($i - 1) > ($fieldKey + 1)) {
                        $fieldList[] = C4GHeadlineField::create($availableField,
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$availableField],
                            '',
                            true, false, false, false);
                    }
                } else {
                    switch ($availableField) {
                        case 'addressStreetNumber':
                        case 'addressZip':
                            break;
                        case 'businessHours':
                            $fieldList[] = C4GTextField::create('businessHours',
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['businessHours'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['businessHours'][1],
                                true, false, true, false)
                                ->setSimpleTextWithoutEditing()
                                ->setEncodeHtmlEntities(false);
                            break;
                        case 'phone':
                            $fieldList[] = C4GLinkField::create('phone',
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['phone'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['phone'][1],
                                true, false, true, false)
                                ->setAddStrBeforeValue('Tel.: ')
                                ->setShowIfEmpty(false)
                                ->setLinkType(C4GLinkField::LINK_TYPE_PHONE);
                            break;
                        case 'mobile':
                            $fieldList[] = C4GLinkField::create('mobile',
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['mobile'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['mobile'][1],
                                true, false, true, false)
                                ->setAddStrBeforeValue('Mobil: ')
                                ->setShowIfEmpty(false)
                                ->setLinkType(C4GLinkField::LINK_TYPE_PHONE);
                            break;
                        case 'fax':
                            $fieldList[] = C4GTextField::create('fax',
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['fax'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['fax'][1],
                                true, false, true, false)
                                ->setAddStrBeforeValue('Fax: ')
                                ->setShowIfEmpty(false);
                            break;
                        case 'email':
                            $fieldList[] = C4GLinkField::create('email',
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['email'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['email'][1],
                                true, false, true, false)
                                ->setAddStrBeforeValue('Email: ')
                                ->setShowIfEmpty(false)
                                ->setLinkType(C4GLinkField::LINK_TYPE_EMAIL);
                            break;
                        case 'website':
                            $fieldList[] = C4GLinkField::create('website',
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['website'][0],
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['website'][1],
                                true, false, true, false)
                                ->setAddStrBeforeValue('Website: ')
                                ->setShowIfEmpty(false)
                                ->setLinkType(C4GLinkField::LINK_TYPE_DEFAULT)
                                ->setNewTab();
                            break;
                        case 'image':
                            $fieldList[] = C4GImageField::create($availableField,
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$availableField][0],
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$availableField][1],
                                true, false, true, false);
                            breaK;
                        case 'linkWizard':
                            foreach (StringUtil::deserialize($model->linkWizard) as $link) {
                                $fieldList[] = C4GTextField::create($link['linkTitle'],
                                    $link['linkTitle'], '', true,
                                    false, false, false)
                                ->setAddStrBeforeValue($link['linkHref'])
                                ->setShowIfEmpty(true);
                            }
                            breaK;
                        default:
                            $fieldList[] = C4GTextField::create($availableField,
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$availableField][0],
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$availableField][1],
                                true, false, true, false)
                                ->setShowIfEmpty(false);
                    }
                }
            }
        }

        return $fieldList;
    }
}