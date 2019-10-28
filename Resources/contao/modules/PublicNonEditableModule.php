<?php
/**
 * Created by PhpStorm.
 * User: cro
 * Date: 19.04.2017
 * Time: 15:12
 */

namespace con4gis\MapContentBundle\Resources\contao\modules;

use con4gis\CoreBundle\Resources\contao\classes\ResourceLoader;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use con4gis\MapContentBundle\Resources\contao\models\PublicNonEditableModel;
use con4gis\ProjectsBundle\Classes\Conditions\C4GBrickCondition;
use con4gis\ProjectsBundle\Classes\Conditions\C4GBrickConditionType;
use con4gis\ProjectsBundle\Classes\Database\C4GBrickDatabaseType;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GHeadlineField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GImageField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GKeyField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GLinkField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GMapLinkButtonField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GMultiCheckboxField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GSelectField;
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
    protected $loadTriggerSearchFromOtherModuleResources = false;
    protected $loadMoreButtonResources = true;
    protected $jQueryUseMaps = true;
    protected $jQueryUseMapsEditor = true;
    protected $loadCkEditor5Resources = true;
    protected $loadMultiColumnResources = true;

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
//        $this->dialogParams->deleteButton(C4GBrickConst::BUTTON_DELETE);
//        $this->dialogParams->deleteButton(C4GBrickConst::BUTTON_SAVE);
        $this->dialogParams->setTabContent(false);
        $this->dialogParams->setWithLabels(false);
        $this->dialogParams->setWithDescriptions(false);
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

        $fieldList[] = C4GHeadlineField::create('data',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['data_legend'],
            '',
            true, false, false, false);

        $fieldList[] = C4GTextField::create('name',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['name'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['name'][1],
            true, true, true, false)
            ->setWithoutLabel();

        $fieldList[] = C4GSelectField::create('type',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['type'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['type'][1],
            false, false, true, false)
            ->setOptions(MapcontentTypeModel::findAll()->fetchAll());

        $fieldList[] = C4GTextField::create('description',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['description'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['description'][1],
            true, false, true, false)
            ->setSimpleTextWithoutEditing();

        $typeModels = MapcontentTypeModel::findAll();
        $conditions = [];
        foreach ($typeModels as $model) {
            if ($GLOBALS['con4gis']['map-content']['frontend']['image'][$model->type]) {
                $conditions[] = new C4GBrickCondition(
                    C4GBrickConditionType::VALUESWITCH,
                    'type',
                    $model->id
                );
            }
        }

        $fieldList[] = C4GImageField::create('image',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['image'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['image'][1],
            true, false, true, false)
            ->setCondition($conditions);

        $conditions = [];
        foreach ($typeModels as $model) {
            if ($GLOBALS['con4gis']['map-content']['frontend']['accessibility'][$model->type]) {
                $conditions[] = new C4GBrickCondition(
                    C4GBrickConditionType::VALUESWITCH,
                    'type',
                    $model->id
                );
            }
        }

        if (isset($GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['accessibility']) === true) {
            $fieldList[] = C4GTextField::create('accessibility',
                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['accessibility'][0],
                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['accessibility'][1],
                true, false, true, false)
                ->setCondition($conditions)
                ->setShowIfEmpty(false);
        }

        $conditions = [];
        foreach ($typeModels as $model) {
            if ($GLOBALS['con4gis']['map-content']['frontend']['address'][$model->type]) {
                $conditions[] = new C4GBrickCondition(
                    C4GBrickConditionType::VALUESWITCH,
                    'type',
                    $model->id
                );
            }
        }

        $fieldList[] = C4GHeadlineField::create('address',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['address_legend'],
            '',
            true, false, false, false);

        $fieldList[] = C4GTextField::create('addressName',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressName'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressName'][1],
            true, true, true, false)
            ->setShowIfEmpty(false)
            ->setCondition($conditions);

        $fieldList[] = C4GTextField::create('addressStreet',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressStreet'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressStreet'][1],
            true, true, true, false)
            ->setCondition($conditions);

        $fieldList[] = C4GTextField::create('addressCity',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressCity'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressCity'][1],
            true, true, true, false)
            ->setCondition($conditions);

//        $fieldList[] = C4GTextField::create('businessHours',
//            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['businessHours'][0],
//            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['businessHours'][1],
//            true, true, true, false);

        $conditions = [];
        foreach ($typeModels as $model) {
            if ($GLOBALS['con4gis']['map-content']['frontend']['contact'][$model->type]) {
                $conditions[] = new C4GBrickCondition(
                    C4GBrickConditionType::VALUESWITCH,
                    'type',
                    $model->id
                );
            }
        }

        $fieldList[] = C4GHeadlineField::create('contact',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['contact_legend'],
            '',
            true, false, false, false);

        $fieldList[] = C4GLinkField::create('phone',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['phone'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['phone'][1],
            true, true, true, false)
            ->setAddStrBeforeValue('Tel.: ')
            ->setShowIfEmpty(false)
            ->setLinkType(C4GLinkField::LINK_TYPE_PHONE)
            ->setCondition($conditions);

        $fieldList[] = C4GLinkField::create('mobile',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['mobile'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['mobile'][1],
            true, true, true, false)
            ->setAddStrBeforeValue('Mobil: ')
            ->setShowIfEmpty(false)
            ->setLinkType(C4GLinkField::LINK_TYPE_PHONE)
            ->setCondition($conditions);

        $fieldList[] = C4GTextField::create('fax',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['fax'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['fax'][1],
            true, true, true, false)
            ->setAddStrBeforeValue('Fax: ')
            ->setShowIfEmpty(false)
            ->setCondition($conditions);

        $fieldList[] = C4GLinkField::create('email',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['email'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['email'][1],
            true, true, true, false)
            ->setAddStrBeforeValue('Email: ')
            ->setShowIfEmpty(false)
            ->setLinkType(C4GLinkField::LINK_TYPE_EMAIL)
            ->setCondition($conditions);

        $fieldList[] = C4GLinkField::create('website',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['website'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['website'][1],
            true, true, true, false)
            ->setAddStrBeforeValue('Website: ')
            ->setShowIfEmpty(false)
            ->setLinkType(C4GLinkField::LINK_TYPE_DEFAULT)
            ->setCondition($conditions);

        $fieldList[] = C4GHeadlineField::create('filter',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['filter_legend'],
            '',
            true, false, false, false);

        foreach ($typeModels as $model) {
            if ($this->c4g_mapcontent_type === $model->id && $GLOBALS['con4gis']['mapcontent_type_filters'][$model->type] !== null) {
                foreach ($GLOBALS['con4gis']['mapcontent_type_filters'][$model->type] as $filter) {
                    $options = [];
                    foreach ($GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$filter.'_option'] as $key => $value) {
                        $options[] = ['id' => $key, 'name' => $value];
                    }
                    $fieldList[] = C4GMultiCheckboxField::create($filter,
                        $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$filter][0],
                        $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$filter][1],
                        true, false, true, false)
                        ->setShowAsCsv()
                        ->setShowIfEmpty(false)
                        ->setOptions($options);

                }
            }
        }

        $fieldList[] = C4GMapLinkButtonField::create('maplink')
            ->setTargetPageId($this->mapPage)
            ->setButtonLabel('zur Karte')
            ->setLongitudeColumn('geox')
            ->setLatitudeColumn('geoy');

        return $fieldList;
    }
}