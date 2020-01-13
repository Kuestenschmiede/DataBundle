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
use con4gis\MapContentBundle\Resources\contao\models\PublicNonEditableModel;
use con4gis\ProjectsBundle\Classes\Database\C4GBrickDatabaseType;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GHeadlineField;
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
        
        if (strval($this->caption) !== '') {
            $this->dialogParams->setBrickCaption(strval($this->caption));
        }

        if (strval($this->captionPlural) !== '') {
            $this->dialogParams->setBrickCaptionPlural(strval($this->captionPlural));
        }
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
            strval($GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['email'][0]),
            strval($GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['email'][1]),
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

        $fieldList[] = C4GMultiLinkField::create('linkWizard',
            '', '', false,
            true, true, false);

        $customFields = MapcontentCustomFieldModel::findAll();

        if ($customFields !== null) {
            foreach ($customFields as $customField) {
                if ($customField->frontendList === '1' && ($customField->type !== 'link')) {
                    $fieldList[] = C4GTextField::create($customField->alias,
                        $customField->name,
                        $customField->description,
                        false, true, true, false
                    );
                }
            }
        }

            $fieldList[] = C4GTextField::create('searchInfo',
                '',
                '',
                false, true, true, false)
                ->setShowIfEmpty(false)
                ->setHidden();

            $fieldList[] = C4GMapLinkButtonField::create('maplink')
                ->setTargetPageId($this->mapPage)
                ->setButtonLabel('zur Karte')
                ->setLongitudeColumn('geox')
                ->setLatitudeColumn('geoy')
                ->setnewTab();

        if ($customFields !== null) {
            foreach ($customFields as $customField) {
                if ($customField->frontendList === '1' && $customField->type === 'link') {
                    $link = C4GLinkButtonField::create($customField->alias);
                    $link->setButtonLabel($customField->linkTitle)
                        ->setNewTab($customField->linkNewTab === '1')
                        ->setTargetMode(C4GLinkButtonField::TARGET_MODE_URL)
                        ->setTargetPageUrl($customField->linkHref)
                        ->setConditional()
                        ->setFormField(false);
                    $fieldList[] = $link;
                }
            }
        }

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

        $availableFieldsDetails = [
            'image',
            'address_legend',
            'addressName',
            'addressStreet',
            'addressCity',
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

        if ($customFields !== null) {
            foreach ($customFields as $customField) {
                if ($customField->frontendDetails === '1') {
                    $availableFieldsDetails[] = $customField->alias;
                }
            }
        }

        $legend = null;
        foreach ($availableFieldsDetails as $field) {
            $model = MapcontentCustomFieldModel::findBy('alias', $field);
            if ($model !== null && $model->type === 'legend') {
                $legend = C4GHeadlineField::create($field,
                    $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$field],
                    '',
                    true, false, false, false);
                $fieldList[] = $legend;
            } elseif ($model === null && C4GUtils::endsWith($field, '_legend') === true) {
                $legend = C4GHeadlineField::create($field,
                    $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$field],
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
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['businessHours'][0],
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['businessHours'][1],
                            true, false, true, false)
                            ->setSimpleTextWithoutEditing()
                            ->setEncodeHtmlEntities(false)
                            ->setShowIfEmpty(false);
                        break;
                    case 'phone':
                        $brickField = C4GLinkField::create('phone',
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['phone'][0],
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['phone'][1],
                            true, false, true, false)
                            ->setAddStrBeforeValue('Tel.: ')
                            ->setShowIfEmpty(false)
                            ->setLinkType(C4GLinkField::LINK_TYPE_PHONE);
                        break;
                    case 'mobile':
                        $brickField = C4GLinkField::create('mobile',
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['mobile'][0],
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['mobile'][1],
                            true, false, true, false)
                            ->setAddStrBeforeValue('Mobil: ')
                            ->setShowIfEmpty(false)
                            ->setLinkType(C4GLinkField::LINK_TYPE_PHONE);
                        break;
                    case 'fax':
                        $brickField = C4GTextField::create('fax',
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['fax'][0],
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['fax'][1],
                            true, false, true, false)
                            ->setAddStrBeforeValue('Fax: ')
                            ->setShowIfEmpty(false);
                        break;
                    case 'email':
                        $brickField = C4GLinkField::create('email',
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['email'][0],
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['email'][1],
                            true, false, true, false)
                            ->setAddStrBeforeValue('Email: ')
                            ->setShowIfEmpty(false)
                            ->setLinkType(C4GLinkField::LINK_TYPE_EMAIL);
                        break;
                    case 'website':
                        $brickField = C4GLinkField::create('website',
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['website'][0],
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['website'][1],
                            true, false, true, false)
                            ->setAddStrBeforeValue('Website: ')
                            ->setShowIfEmpty(false)
                            ->setLinkType(C4GLinkField::LINK_TYPE_DEFAULT)
                            ->setNewTab();
                        break;
                    case 'image':
                        $brickField = C4GImageField::create('image',
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['image'][0],
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['image'][1],
                            true, false, true, false);
                        break;
                    case 'linkWizard':
                        $brickField = C4GMultiLinkField::create('linkWizard',
                            '', '', true,
                            false, true, false);
                        break;
                    default:
                        $brickField = C4GTextField::create($field,
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$field][0],
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$field][1],
                            true, false, true, false)
                            ->setShowIfEmpty(false);
                }
                if ($brickField !== null) {
                    $fieldList[] = $brickField;
                }
                if ($legend !== null && $brickField !== null) {
                    $legend->addAssociatedField($brickField);
                }
            } else {
                $brickField = C4GTextField::create($field,
                    strval($model->name),
                    strval($model->description),
                    true, false, true, false)
                    ->setShowIfEmpty(false);
                $fieldList[] = $brickField;
                if ($legend !== null && $brickField !== null) {
                    $legend->addAssociatedField($brickField);
                }
            }
        }



        return $fieldList;
    }
}