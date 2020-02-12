<?php


namespace con4gis\DataBundle\Resources\contao\models;

use con4gis\CoreBundle\Classes\Helper\ArrayHelper;
use con4gis\DataBundle\Resources\contao\modules\PublicNonEditableModule;
use con4gis\GroupsBundle\Resources\contao\models\MemberModel;
use con4gis\ProjectsBundle\Classes\Common\C4GBrickCommon;
use Contao\Database;
use Contao\StringUtil;

class MemberEditableModel
{
    public static function find($memberId, $tableName, $database, $fieldList, $listParams) {

        $db = \Database::getInstance();

        $memberModel = MemberModel::findByPk($memberId);
        $groups = StringUtil::deserialize($memberModel->groups);
        $where = [];
        foreach ($groups as $group) {
            $where[] = 'ownerGroupId = ' . $group;
        }

        $stmt = $db->prepare("SELECT * FROM $tableName WHERE " . implode(' OR ', $where));
        $result = $stmt->execute($memberId)->fetchAllAssoc();

        foreach ($result as $key => $row) {
            if ($row['datePublished']) {
                $result[$key]['datePublished'] = date('d.m.Y', $row['datePublished']);
            } else {
                $result[$key]['datePublished'] = '';
            }
        }

        return ArrayHelper::arrayToObject($result);
    }

    public static function findByPk($pk) {
        $model = DataElementModel::findByPk($pk);
        if ($model !== null) {
            $array = $model->row();
            return ArrayHelper::arrayToObject($array);
        } else {
            return ArrayHelper::arrayToObject([]);
        }
    }
}