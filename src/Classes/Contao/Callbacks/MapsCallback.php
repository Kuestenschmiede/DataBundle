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
namespace con4gis\DataBundle\Classes\Contao\Callbacks;

use con4gis\DataBundle\Classes\Models\DataCustomFieldModel;
use con4gis\DataBundle\Classes\Models\DataDirectoryModel;
use con4gis\DataBundle\Classes\Models\DataTypeModel;
use Contao\Backend;
use Contao\System;

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
    public function getSearchFields($multiColumnWizard)
    {
        $arrColumnTypes = [
            'label' => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['searchFields']['name'],
            'inputType' => 'select',
            'eval' => ['chosen' => true, 'includeBlankOption' => true, 'style' => 'min-width:200px;width:200px;', 'tl_class' => 'w50'],
        ];
        $dc = [];
        $arrOptions = $this->loadAvailableFieldsOptions($dc);
        $arrColumnTypes['options'] = $arrOptions;

        $arrColumnWeight = [
            'label' => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['searchFields']['weight'],
            'inputType' => 'text',
            'default' => '20',
            'eval' => ['regxp' => 'digit','tl_class' => 'w50'],
        ];
        $return = [
            'name' => $arrColumnTypes,
            'weight' => $arrColumnWeight,
        ];

        return $return;
    }

    public function loadAvailableFieldsOptions($dc)
    {
        System::loadLanguageFile('tl_c4g_data_element');
        $language = $GLOBALS['TL_LANG']['tl_c4g_data_element'];
        $options = [
            'name' => $language['name'][0],
            'addressName' => $language['addressName'][0],
            'addressStreet' => $language['addressStreet'][0],
            'addressStreetNumber' => $language['addressStreetNumber'][0],
            'addressZip' => $language['addressZip'][0],
            'addressCity' => $language['addressCity'][0],
            'addressState' => $language['addressState'][0],
            'addressCountry' => $language['addressCountry'][0],
            'description' => $language['description'][0],
        ];
        $customFields = DataCustomFieldModel::findAll();
        if ($customFields !== null) {
            foreach ($customFields as $customField) {
                if ($customField->type === 'text' || $customField->type === 'textarea' || $customField->type === 'texteditor') {
                    $label = strval($customField->name);
                    $options[strval($customField->alias)] = $label;
                }
            }
        }

        return $options;
    }
}
