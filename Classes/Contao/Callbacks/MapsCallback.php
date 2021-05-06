<?php
/*
 * This file is part of con4gis, the gis-kit for Contao CMS.
 * @package con4gis
 * @version 8
 * @author con4gis contributors (see "authors.txt")
 * @license LGPL-3.0-or-later
 * @copyright (c) 2010-2021, by Küstenschmiede GmbH Software & Design
 * @link https://www.con4gis.org
 */
namespace con4gis\DataBundle\Classes\Contao\Callbacks;

use con4gis\DataBundle\Resources\contao\models\DataDirectoryModel;
use con4gis\DataBundle\Resources\contao\models\DataTypeModel;
use Contao\Backend;

class MapsCallback extends Backend
{
    public function getConfiguredTypes()
    {
        $arrTypes = [];
        $t = 'tl_c4g_data_type';
        $arrOptions = [
            'order' => "$t.categorySort ASC, $t.name ASC",
        ];
        $types = DataTypeModel::findAll($arrOptions);
        foreach ($types as $type) {
            $arrTypes[$type->id] = $type->name;
        }

        return $arrTypes;
    }

    public function getConfiguredDirectories()
    {
        $arrTypes = [];
        $t = 'tl_c4g_data_directory';
        $arrOptions = [
            'order' => "$t.name ASC",
        ];
        $types = DataDirectoryModel::findAll($arrOptions);
        foreach ($types as $type) {
            $arrTypes[$type->id] = $type->name;
        }

        return $arrTypes;
    }
}
