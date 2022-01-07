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

use con4gis\CoreBundle\Classes\C4GUtils;
use con4gis\DataBundle\Classes\Models\DataCustomFieldModel;
use con4gis\DataBundle\Classes\Models\C4gMapLocstylesModel;
use Contao\Backend;
use Contao\StringUtil;
use Contao\System;

class TypeCallback extends Backend
{
    public function getLocstyles()
    {
        $t = 'tl_c4g_map_locstyles';
        $arrOptions = [
            'order' => "$t.name ASC",
        ];
        $locstyles = C4gMapLocstylesModel::findAll($arrOptions);
        $arrLocstyles = [];
        foreach ($locstyles as $locstyle) {
            $arrLocstyles[$locstyle->id] = $locstyle->name;
        }

        return $arrLocstyles;
    }

    public function getLabel($arrRow)
    {
        $label['name'] = $arrRow['name'];
        $label['availableFields'] = '';
        System::loadLanguageFile('tl_c4g_data_element');
        foreach (StringUtil::deserialize($arrRow['availableFields']) as $field) {
            $model = DataCustomFieldModel::findOneBy('alias', $field);
            if ($model !== null) {
                if ($label['availableFields'] === '') {
                    $label['availableFields'] = $model->name;
                } else {
                    $label['availableFields'] .= ', ' . $model->name;
                }
            } else {
                if (C4GUtils::endsWith($field, 'legend') === true) {
                    if ($label['availableFields'] === '') {
                        $label['availableFields'] = $GLOBALS['TL_LANG']['tl_c4g_data_element'][$field];
                    } else {
                        $label['availableFields'] .= ', ' . $GLOBALS['TL_LANG']['tl_c4g_data_element'][$field];
                    }
                } else {
                    if ($label['availableFields'] === '') {
                        $label['availableFields'] = $GLOBALS['TL_LANG']['tl_c4g_data_element'][$field][0];
                    } else {
                        $label['availableFields'] .= ', ' . $GLOBALS['TL_LANG']['tl_c4g_data_element'][$field][0];
                    }
                }
            }
        }

        return $label;
    }

    public function getTypeOptions($dc)
    {
        $types = [];
        foreach ($GLOBALS['con4gis']['data_types'] as $type) {
            $types[$type] = $GLOBALS['TL_LANG']['data_types'][$type];
        }

        return $types;
    }

    public function loadItemTypeOptions($dc)
    {
        return $GLOBALS['TL_LANG']['tl_c4g_data_type']['itemTypeOptions'];
    }

    public function loadAvailableFieldsOptions($dc)
    {
        System::loadLanguageFile('tl_c4g_data_element');
        $language = $GLOBALS['TL_LANG']['tl_c4g_data_element'];
        $options = [
            'businessHours_legend' => '<strong>' . $GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] . $language['businessHours_legend'] . '</strong>',
            'businessHours' => $language['businessHours'][0] .
                " <sup title='" . $language['businessHours'][1] . "'>(?)</sup>",
            'address_legend' => '<strong>' . $GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] . $language['address_legend'] . '</strong>',
            'addressName' => $language['addressName'][0] .
                " <sup title='" . $language['addressName'][1] . "'>(?)</sup>",
            'addressStreet' => $language['addressStreet'][0] .
                " <sup title='" . $language['addressStreet'][1] . "'>(?)</sup>",
            'addressStreetNumber' => $language['addressStreetNumber'][0],
            'addressZip' => $language['addressZip'][0],
            'addressCity' => $language['addressCity'][0],
            'addressState' => $language['addressState'][0],
            'addressCountry' => $language['addressCountry'][0],
            'contact_legend' => '<strong>' . $GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] . $language['contact_legend'] . '</strong>',
            'phone' => $language['phone'][0],
            'mobile' => $language['mobile'][0],
            'fax' => $language['fax'][0],
            'email' => $language['email'][0],
            'website' => $language['website'][0] .
                " <sup title='" . $language['website'][1] . "'>(?)</sup>",
            'websiteLabel' => $language['websiteLabel'][0] .
                " <sup title='" . $language['websiteLabel'][1] . "'>(?)</sup>",
            'image_legend' => '<strong>' . $GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] . $language['image_legend'] . '</strong>',
            'image' => $language['image'][0] .
                " <sup title='" . $language['image'][1] . "'>(?)</sup>",
            'accessibility_legend' => '<strong>' . $GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] . $language['accessibility_legend'] . '</strong>',
            'accessibility' => $language['accessibility'][0] .
                " <sup title='" . $language['accessibility'][1] . "'>(?)</sup>",
            'linkWizard_legend' => '<strong>' . $GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] . $language['linkWizard_legend'] . '</strong>',
            'linkWizard' => $language['linkWizard'][0] .
                " <sup title='" . $language['linkWizard'][1] . "'>(?)</sup>",
            'osm_legend' => '<strong>' . $GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] . $language['osm_legend'] . '</strong>',
            'osmId' => $language['osmId'][0] .
                " <sup title='" . $language['osmId'][1] . "'>(?)</sup>",
            'publish_legend' => '<strong>' . $GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] . $language['publish_legend'] . '</strong>',
            'publishFrom' => $language['publishFrom'][0] .
                " <sup title='" . $language['publishFrom'][1] . "'>(?)</sup>",
            'publishTo' => $language['publishTo'][0] .
                " <sup title='" . $language['publishTo'][1] . "'>(?)</sup>",
        ];
        $customFields = DataCustomFieldModel::findAll();
        if ($customFields !== null) {
            foreach ($customFields as $customField) {
                if ($customField->type === 'legend') {
                    $label = '<strong>' . $GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] . strval($customField->name) . '</strong>';
                    $options[strval($customField->alias)] = $label;
                } else {
                    $label = strval($customField->name);
                    if (strval($customField->description) !== '') {
                        $label .= " <sup title='" . strval($customField->description) . "'>(?)</sup>";
                    }
                    $options[strval($customField->alias)] = $label;
                }
            }
        }

        return $options;
    }
}
