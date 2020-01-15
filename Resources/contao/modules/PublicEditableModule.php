<?php
/**
 * Created by PhpStorm.
 * User: cro
 * Date: 19.04.2017
 * Time: 15:12
 */

namespace con4gis\MapContentBundle\Resources\contao\modules;

use con4gis\CoreBundle\Classes\ResourceLoader;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use con4gis\MapContentBundle\Resources\contao\models\PublicNonEditableModel;
use con4gis\ProjectsBundle\Classes\Common\C4GBrickConst;
use con4gis\ProjectsBundle\Classes\Conditions\C4GBrickCondition;
use con4gis\ProjectsBundle\Classes\Conditions\C4GBrickConditionType;
use con4gis\ProjectsBundle\Classes\Database\C4GBrickDatabaseType;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GCheckboxField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GCKEditor5Field;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GFileField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GGeopickerField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GKeyField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GMultiCheckboxField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GMultiColumnField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GSelectField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GTextField;
use con4gis\ProjectsBundle\Classes\Framework\C4GBrickModuleParent;
use con4gis\ProjectsBundle\Classes\Lists\C4GBrickRenderMode;
use con4gis\ProjectsBundle\Classes\Views\C4GBrickViewType;


class PublicEditableModule extends C4GBrickModuleParent
{
    protected $viewType             = C4GBrickViewType::PUBLICBASED;
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

    public function initBrickModule($id)
    {
        parent::initBrickModule($id);

        //Parameter zur Liste
        $this->listParams->setRenderMode(C4GBrickRenderMode::TABLEBASED);
        $this->listParams->setWithExportButtons(false);
        $this->listParams->setDisplayLength(50);
        $this->listParams->setLengthChange(false);
        $this->listParams->setPaginate(true);

        $this->listParams->setWithDetails(true);
        $this->dialogParams->deleteButton(C4GBrickConst::BUTTON_DELETE);
        $this->dialogParams->setAccordion(true);
        $this->dialogParams->addOnLoadScript("readInitialValues();");
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

        $fieldList[] = C4GTextField::create('name',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['name'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['name'][1],
            true, true, true, true);

        $type = C4GSelectField::create('type',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['type'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['type'][1],
            true, true, true, true)
            ->setCallOnChange()
            ->setOptions(MapcontentTypeModel::findAll()->fetchAll());
        $fieldList[] = $type;

        $fieldList[] = C4GTextField::create('address',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['address'][0], '',
            false, true, true, false);

        $fieldList[] = C4GCKEditor5Field::create('description',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['description'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['description'][1],
            true, false, true, true);

        $typeModels = MapcontentTypeModel::findAll();
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

        $fieldList[] = C4GTextField::create('addressName',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressName'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressName'][1],
            true, false, true, true)
            ->setCondition($conditions);

        $fieldList[] = C4GTextField::create('addressStreet',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressStreet'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressStreet'][1],
            true, false, true, true)
            ->setCondition($conditions);

        $fieldList[] = C4GTextField::create('addressStreetNumber',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressStreetNumber'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressStreetNumber'][1],
            true, false, true, true)
            ->setCondition($conditions);

        $fieldList[] = C4GTextField::create('addressZip',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressZip'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressZip'][1],
            true, false, true, true)
            ->setCondition($conditions);

        $fieldList[] = C4GTextField::create('addressCity',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressCity'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressCity'][1],
            true, false, true, true)
            ->setCondition($conditions);

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

        $fieldList[] = C4GTextField::create('phone',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['phone'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['phone'][1],
            true, false, true, true)
            ->setCondition($conditions);

        $fieldList[] = C4GTextField::create('fax',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['fax'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['fax'][1],
            true, false, true, true)
            ->setCondition($conditions);

        $fieldList[] = C4GTextField::create('email',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['email'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['email'][1],
            true, false, true, true)
            ->setCondition($conditions);

        $fieldList[] = C4GTextField::create('website',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['website'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['website'][1],
            true, false, true, true)
            ->setCondition($conditions);

        foreach ($typeModels as $model) {
            if ($GLOBALS['con4gis']['mapcontent_type_filters'][$model->type] !== null) {
                $condition = new C4GBrickCondition(
                    C4GBrickConditionType::VALUESWITCH,
                    'type',
                    $model->id
                );
                foreach ($GLOBALS['con4gis']['mapcontent_type_filters'][$model->type] as $filter) {
                    $options = [];
                    foreach ($GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$filter.'_option'] as $key => $value) {
                        $options[] = ['id' => $key, 'name' => $value];
                    }
                    $fieldList[] = C4GMultiCheckboxField::create($filter,
                        $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$filter][0],
                        $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$filter][1],
                        true, false, true, true)
                        ->setCondition($condition)
                        ->setOptions($options);

                }
            }
        }

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

        $fieldList[] = C4GFileField::create('image',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['image'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['image'][1],
            true, false, true, true)
            ->setCondition($conditions)
            ->setLinkType(C4GFileField::LINK_TYPE_IMAGE);

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

        $fieldList[] = C4GCheckboxField::create('accessibility',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['accessibility'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['accessibility'][1],
            true, false, true, true)
            ->setCondition($conditions);

        $fieldList[] = C4GMultiColumnField::create('businessHours',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['businessHours'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['businessHours'][1],
            true, false, true, true)
            ->addInputField('dayFrom', 'text',
                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['dayFrom'][0])
            ->addInputField('dayTo', 'text',
                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['dayTo'][0])
            ->addInputField('timeFrom', 'text',
                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['timeFrom'][0])
            ->addInputField('timeTo', 'text',
                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['timeTo'][0]);
//            ->setCondition($conditions);

        $fieldList[] = C4GGeopickerField::create('geo',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['location'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['location'][1],
            true, false, true, true)
            ->setLocGeoxFieldname('geox')
            ->setLocGeoyFieldname('geoy')
            ->setWithoutAddressRow(true);

        return $fieldList;
    }
}