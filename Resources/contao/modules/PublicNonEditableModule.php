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
use con4gis\ProjectsBundle\Classes\Common\C4GBrickConst;
use con4gis\ProjectsBundle\Classes\Conditions\C4GBrickCondition;
use con4gis\ProjectsBundle\Classes\Conditions\C4GBrickConditionType;
use con4gis\ProjectsBundle\Classes\Database\C4GBrickDatabaseType;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GCKEditor5Field;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GGeopickerField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GHeadlineField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GKeyField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GSelectField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GTextareaField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GTextField;
use con4gis\ProjectsBundle\Classes\Framework\C4GBrickModuleParent;
use con4gis\ProjectsBundle\Classes\Lists\C4GBrickRenderMode;
use con4gis\ProjectsBundle\Classes\Views\C4GBrickViewType;


class PublicNonEditableModule extends C4GBrickModuleParent
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
    protected $jQueryUseMaps = false;
    protected $loadCkEditor5Resources = true;

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

        $fieldList[] = C4GHeadlineField::create('data_legend',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['data_legend'],
            '', true, false);

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

        $fieldList[] = C4GHeadlineField::create('location_legend',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['location_legend'],
            '', true, false);

        $fieldList[] = C4GGeopickerField::create('geo',
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['location'][0],
            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['location'][1],
            true, false, true, true)
            ->setLocGeoxFieldname('geox')
            ->setLocGeoyFieldname('geoy');

        return $fieldList;
    }
}