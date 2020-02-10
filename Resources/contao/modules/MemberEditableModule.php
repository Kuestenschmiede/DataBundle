<?php
/**
 * Created by PhpStorm.
 * User: cro
 * Date: 19.04.2017
 * Time: 15:12
 */

namespace con4gis\DataBundle\Resources\contao\modules;

use con4gis\CoreBundle\Classes\C4GUtils;
use con4gis\CoreBundle\Classes\Callback\C4GObjectCallback;
use con4gis\CoreBundle\Classes\ResourceLoader;
use con4gis\CoreBundle\Resources\contao\models\C4gLogModel;
use con4gis\DataBundle\Resources\contao\models\DataCustomFieldModel;
use con4gis\DataBundle\Resources\contao\models\MemberEditableModel;
use con4gis\ProjectsBundle\Classes\Database\C4GBrickDatabaseType;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GHeadlineField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GKeyField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GMultiCheckboxField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GSelectField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GTextField;
use con4gis\ProjectsBundle\Classes\Framework\C4GBrickModuleParent;
use con4gis\ProjectsBundle\Classes\Lists\C4GBrickRenderMode;
use con4gis\ProjectsBundle\Classes\Permission\C4GTablePermission;
use con4gis\ProjectsBundle\Classes\Views\C4GBrickViewType;
use Contao\Database;
use Contao\StringUtil;


class MemberEditableModule extends C4GBrickModuleParent
{
    protected $viewType             = C4GBrickViewType::MEMBERBASED;
    protected $languageFile         = 'tl_c4g_data_element';

    protected $modelClass           = MemberEditableModel::class;
    protected $modelListFunction    = 'find';

    protected $databaseType         = C4GBrickDatabaseType::DCA_MODEL;
    protected $tableName            = 'tl_c4g_data_element';

    protected $loadConditionalFieldDisplayResources = false;
    protected $loadTriggerSearchFromOtherModuleResources = false;
    protected $loadMoreButtonResources = false;
    protected $jQueryUseMaps = false;
    protected $jQueryUseMapsEditor = false;
    protected $loadCkEditor5Resources = false;
    protected $loadMultiColumnResources = false;
    protected $loadMiniSearchResources = false;

    public function initBrickModule($id)
    {
        parent::initBrickModule($id);

        //Parameter zur Liste
        $this->listParams->setRenderMode(C4GBrickRenderMode::TABLEBASED);
        $this->listParams->setWithExportButtons(false);
        $this->listParams->setDisplayLength(50);
        $this->listParams->setLengthChange(false);
        $this->listParams->setPaginate(true);

        $this->listParams->setShowItemType();
        $this->dialogParams->setTabContent(false);
        $this->dialogParams->setWithLabels(true);
        $this->dialogParams->setWithDescriptions(true);
        $this->dialogParams->setId($id);

        $this->viewParams->setMemberKeyField('mitglied');
        $this->dialogParams->setModelDialogFunction('findByPk');
        $this->dialogParams->setSaveCallBack(new C4GObjectCallback($this, 'saveCallback'));

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

        $availableFields = StringUtil::deserialize($this->availableFieldsList);
        if ($availableFields !== null) {
            foreach ($availableFields as $availableField) {
                $customField = DataCustomFieldModel::findBy('alias', $availableField);
                if ($customField !== null) {
                    switch ($customField->type) {
                        case 'text':
                            $fieldList[] = C4GTextField::create($customField->alias,
                                $customField->frontendName ?: $customField->name ?: '',
                                $customField->description ?: '',
                                true, true, true, true);
                            break;
                        case 'select':
                            $field = C4GSelectField::create($customField->alias,
                                $customField->frontendName ?: $customField->name ?: '',
                                $customField->description ?: '',
                                true, true, true, true)
                                ->setChosen(true);

                            $options = StringUtil::deserialize($customField->options);
                            $optionsFormatted = [];
                            foreach ($options as $option) {
                                $optionsFormatted[] = [
                                    'id' => $option['key'],
                                    'name' => $option['value']
                                ];
                            }
                            $field->setOptions($optionsFormatted);

                            $fieldList[] = $field;
                            break;
                        case 'multicolumn':
                            $field = C4GMultiCheckboxField::create($customField->alias,
                                $customField->frontendName ?: $customField->name ?: '',
                                $customField->description ?: '',
                                true, true, true, true);

                            $options = StringUtil::deserialize($customField->options);
                            $optionsFormatted = [];
                            foreach ($options as $option) {
                                $optionsFormatted[] = [
                                    'id' => $option['key'],
                                    'name' => $option['value']
                                ];
                            }
                            $field->setOptions($optionsFormatted);

                            $fieldList[] = $field;
                            break;
                        default:
                            break;
                    }
                } else {
                    if (!C4GUtils::endsWith($availableField, '_legend')) {
                        $fieldList[] = C4GTextField::create($availableField,
                            $GLOBALS['TL_LANG']['tl_c4g_data_element'][$availableField][0],
                            $GLOBALS['TL_LANG']['tl_c4g_data_element'][$availableField][1],
                            true, true, true, true);
                    } else {
                        $fieldList[] = C4GHeadlineField::create($availableField,
                            $GLOBALS['TL_LANG']['tl_c4g_data_element'][$availableField],
                            $GLOBALS['TL_LANG']['tl_c4g_data_element'][$availableField],
                            true, false, true, false);
                    }
                }
            }
        }

        return $fieldList;
    }

    public function getC4GTablePermission($viewType) {
        $stmt = Database::getInstance()->prepare("SELECT id FROM tl_c4g_data_element WHERE mitglied = ?");
        $result = $stmt->execute($this->dialogParams->getMemberId())->fetchAllAssoc();
        $ids = [];
        foreach ($result as $row) {
            $ids[] = $row['id'];
        }
        return new C4GTablePermission($this->tableName, $ids);
    }

    /**
     * @param $tableName string Sagt uns, in welcher Tabelle gespeichert wurde
     * @param $set array Die gespeicherten Daten als assoz. Array
     * @param $insertId int Die Datensatz ID
     * @param $type string 'insert' => Neuer Datensatz, 'update' => Alter Datensatz aktualisiert
     * @param $fieldList array die Feldliste
     */
    public function saveCallback($tableName, $set, $insertId, $type, $fieldList) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE $tableName SET name = ?, type = 41 WHERE id = ?");
        $stmt->execute('Im Frontend eingetragen', $insertId);
    }
}