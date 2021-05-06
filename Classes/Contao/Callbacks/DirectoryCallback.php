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

use con4gis\DataBundle\Resources\contao\models\DataTypeModel;
use Contao\Backend;

class DirectoryCallback extends Backend
{
    public function loadTypeOptions($dc)
    {
        $t = 'tl_c4g_data_type';
        $arrOptions = [
            'order' => "$t.name ASC",
        ];
        $types = DataTypeModel::findAll($arrOptions);
        $options = [];
        if ($types !== null) {
            foreach ($types as $type) {
                if (intval($type->id) > 0 && strval($type->name) !== '') {
                    $options[$type->id] = $type->name;
                }
            }
        }

        return $options;
    }
}
