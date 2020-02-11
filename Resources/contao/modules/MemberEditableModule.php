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
use con4gis\ProjectsBundle\Classes\Actions\C4GShowListAction;
use con4gis\ProjectsBundle\Classes\Buttons\C4GMoreButton;
use con4gis\ProjectsBundle\Classes\Buttons\C4GMoreButtonEntry;
use con4gis\ProjectsBundle\Classes\Common\C4GBrickConst;
use con4gis\ProjectsBundle\Classes\Conditions\C4GBrickCondition;
use con4gis\ProjectsBundle\Classes\Conditions\C4GBrickConditionType;
use con4gis\ProjectsBundle\Classes\Database\C4GBrickDatabaseType;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GHeadlineField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GKeyField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GMoreButtonField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GMultiCheckboxField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GSelectField;
use con4gis\ProjectsBundle\Classes\Fieldtypes\C4GTextField;
use con4gis\ProjectsBundle\Classes\Framework\C4GBrickModuleParent;
use con4gis\ProjectsBundle\Classes\Lists\C4GBrickRenderMode;
use con4gis\ProjectsBundle\Classes\Permission\C4GTablePermission;
use con4gis\ProjectsBundle\Classes\Views\C4GBrickViewType;
use Contao\Database;
use Contao\FrontendUser;
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
    protected $loadMoreButtonResources = true;
    protected $jQueryUseMaps = false;
    protected $jQueryUseMapsEditor = false;
    protected $loadCkEditor5Resources = false;
    protected $loadMultiColumnResources = false;
    protected $loadMiniSearchResources = false;

    private static $database = null;

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
        $this->listParams->setShowToolTips(false);
        $this->dialogParams->setTabContent(false);
        $this->dialogParams->setWithLabels(true);
        $this->dialogParams->setWithDescriptions(true);
        $this->dialogParams->setId($id);

        $this->viewParams->setMemberKeyField('mitglied');
        $this->dialogParams->setModelDialogFunction('findByPk');
        $this->dialogParams->setSaveCallBack(new C4GObjectCallback($this, 'saveCallback'));

        static::$database = Database::getInstance();
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

        $fieldList[] = C4GTextField::create('datePublished',
            $GLOBALS['TL_LANG']['tl_c4g_data_element']['datePublished'][0],
            $GLOBALS['TL_LANG']['tl_c4g_data_element']['datePublished'][1],
            false, true, true, false);

        $publishCondition = new C4GBrickCondition(C4GBrickConditionType::METHODSWITCH, 'published', '0');
        $publishCondition->setModel(static::class);
        $publishCondition->setFunction('moreButtonPublishCondition');
        $unPublishCondition = new C4GBrickCondition(C4GBrickConditionType::METHODSWITCH, 'published', '1');
        $unPublishCondition->setModel(static::class);
        $unPublishCondition->setFunction('moreButtonUnPublishCondition');

        \System::loadLanguageFile('tl_c4g_data_element');
        $moreButtonEntryPublish = new C4GMoreButtonEntry();
        $moreButtonEntryPublish->setTitle($GLOBALS['TL_LANG']['con4gis']['data']['frontend']['MoreButtonPublish']);
        $moreButtonEntryPublish->setCallable(C4GMoreButtonEntry::CALLMODE_OBJECT, [$this, 'moreButtonPublish']);
        $moreButtonEntryPublish->setToolTip('');
        $moreButtonEntryPublish->setCondition([$publishCondition]);
        $moreButtonEntryUnPublish = new C4GMoreButtonEntry();
        $moreButtonEntryUnPublish->setTitle($GLOBALS['TL_LANG']['con4gis']['data']['frontend']['MoreButtonUnPublish']);
        $moreButtonEntryUnPublish->setCallable(C4GMoreButtonEntry::CALLMODE_OBJECT, [$this, 'moreButtonUnPublish']);
        $moreButtonEntryUnPublish->setToolTip('');
        $moreButtonEntryUnPublish->setCondition([$unPublishCondition]);
        $moreButton = new C4GMoreButton();
        $moreButton->addEntry($moreButtonEntryPublish);
        $moreButton->addEntry($moreButtonEntryUnPublish);
        $moreButton->setRenderModeOverride('entry');

        $fieldList[] = C4GMoreButtonField::create('moreButtonField',
            $GLOBALS['TL_LANG']['con4gis']['data']['frontend']['moreButtonField'],
            '', false, true, false, false,
            [
                'moreButton' => $moreButton,
                'buttonTitle' => $GLOBALS['TL_LANG']['con4gis']['data']['frontend']['MoreButtonButtonTitle'],
            ]);

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

    public function moreButtonPublish() {
        $userId = FrontendUser::getInstance()->id;
        C4gLogModel::addLogEntry('userId', $userId);
        $stmt = static::$database->prepare(
            "SELECT count(*) as current FROM `tl_c4g_data_element` ".
            "where mitglied = ? AND published = 1"
        );
        $current = $stmt->execute($userId)->fetchAllAssoc()[0]['current'];
        $stmt = static::$database->prepare(
            "SELECT numberAdvertisement FROM `tl_member` ".
            "where id = ?"
        );
        $maximum = $stmt->execute($userId)->fetchAllAssoc()[0]['numberAdvertisement'];
        if ($current < $maximum) {
            $id = $this->dialogParams->getId();
            $stmt = static::$database->prepare("UPDATE tl_c4g_data_element SET published = '1', datePublished = ? WHERE id = ?");
            $stmt->execute(time(), $id);
            $this->dialogParams->setId(-1);
            $action = new C4GShowListAction($this->getDialogParams(), $this->getListParams(), $this->getFieldList(), $this->getPutVars(), $this->getBrickDatabase());
            $action->setModule($this);
            $return = $action->run();
            $return['title'] = 'Gepublished';
            $return['usermessage'] = 'Das Element ist gepublished';
            return $return;
        } else {
            $return['title'] = 'Maximale Anzeigen';
            $return['usermessage'] = 'Die maximale Anzahl Anzeigen ist bereits erreicht.';
            return $return;
        }
    }

    public static function moreButtonPublishCondition($id) {
        $stmt = static::$database->prepare("SELECT tl_c4g_data_element.published, tl_c4g_data_type.allowPublishing FROM tl_c4g_data_element JOIN tl_c4g_data_type ON tl_c4g_data_element.type = tl_c4g_data_type.id WHERE tl_c4g_data_element.id = ?");
        $result = $stmt->execute($id)->fetchAllAssoc();
        return $result[0]['published'] === '0' && $result[0]['allowPublishing'] === '1';
    }

    public function moreButtonUnPublish() {
        $id = $this->dialogParams->getId();
        $stmt = static::$database->prepare("UPDATE tl_c4g_data_element SET published = '0' WHERE id = ?");
        $stmt->execute($id);
        $this->dialogParams->setId(-1);
        $action = new C4GShowListAction($this->getDialogParams(), $this->getListParams(), $this->getFieldList(), $this->getPutVars(), $this->getBrickDatabase());
        $action->setModule($this);
        $return = $action->run();
        $return['title'] = 'Geunpublished';
        $return['usermessage'] = 'Das Element ist geunpublished';
        return $return;
        return [];
    }

    public static function moreButtonUnPublishCondition($id) {
        $stmt = static::$database->prepare("SELECT tl_c4g_data_element.published, tl_c4g_data_type.allowPublishing FROM tl_c4g_data_element JOIN tl_c4g_data_type ON tl_c4g_data_element.type = tl_c4g_data_type.id WHERE tl_c4g_data_element.id = ?");
        $result = $stmt->execute($id)->fetchAllAssoc();
        return $result[0]['published'] === '1' && $result[0]['allowPublishing'] === '1';
    }
}