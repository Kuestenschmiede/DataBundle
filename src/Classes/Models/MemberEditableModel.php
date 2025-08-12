<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2022, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */

namespace con4gis\DataBundle\Classes\Models;

use con4gis\CoreBundle\Classes\C4GUtils;
use con4gis\CoreBundle\Classes\Helper\ArrayHelper;
use con4gis\ProjectsBundle\Classes\Common\C4GBrickCommon;
use Contao\Database;
use Contao\MemberModel;
use Contao\StringUtil;

class MemberEditableModel
{
    public static function find($memberId, $tableName, $database, $fieldList, $listParams) {

        $db = Database::getInstance();

        $memberModel = MemberModel::findByPk($memberId);
        $groups = StringUtil::deserialize($memberModel->groups);

        if ($tableName === "") {
            $tableName = "tl_c4g_data_element";
        }

        if ($groups !== null && count($groups) > 0) {
            $sql = "SELECT * FROM $tableName WHERE ownerGroupId " . C4GUtils::buildInString($groups);
            $result = $db->prepare($sql)->execute(...$groups)->fetchAllAssoc();
        } else {
            return ArrayHelper::arrayToObject([]);
        }

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

    public static function findBy($field, $value)
    {
        return DataElementModel::findBy($field, $value);
    }
}