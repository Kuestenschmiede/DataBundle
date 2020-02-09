<?php
/**
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package   	con4gis
 * @version    7
 * @author  	    con4gis contributors (see "authors.txt")
 * @license 	    LGPL-3.0-or-later
 * @copyright 	Küstenschmiede GmbH Software & Design
 * @link              https://www.con4gis.org
 */
namespace con4gis\DataBundle\Classes\Contao\Callbacks;

use con4gis\DataBundle\Resources\contao\models\DataCustomFieldModel;
use Contao\Backend;
use Contao\System;

class ModuleCallback extends Backend
{
    public function loadAvailableFieldsOptions($dc)
    {
        System::loadLanguageFile('tl_c4g_data_element');
        System::loadLanguageFile('tl_c4g_data_type');
        $language = $GLOBALS['TL_LANG']['tl_c4g_data_element'];
        $options = [

            'name' => $language['name'][0] .
                " <sup title='" . $language['name'][1] . "'>(?)</sup>",
            'image' => $language['image'][0] .
                " <sup title='" . $language['image'][1] . "'>(?)</sup>",
            'address' => $language['address'][0] .
                " <sup title='" . $language['address'][1] . "'>(?)</sup>",
            'businessHours' => $language['businessHours'][0] .
                " <sup title='" . $language['businessHours'][1] . "'>(?)</sup>",
            'phone' => $language['phone'][0] .
                " <sup title='" . $language['phone'][1] . "'>(?)</sup>",
            'mobile' => $language['mobile'][0] .
                " <sup title='" . $language['mobile'][1] . "'>(?)</sup>",
            'fax' => $language['fax'][0] .
                " <sup title='" . $language['fax'][1] . "'>(?)</sup>",
            'email' => $language['email'][0] .
                " <sup title='" . $language['email'][1] . "'>(?)</sup>",
            'website' => $language['website'][0] .
                " <sup title='" . $language['website'][1] . "'>(?)</sup>",
            'linkWizard' => $language['linkWizard'][0] .
                " <sup title='" . $language['linkWizard'][1] . "'>(?)</sup>",

            'data_legend' => '<strong>' . $GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] . $language['data_legend'] . '</strong>',
            'address_legend' => '<strong>' . $GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] . $language['address_legend'] . '</strong>',
            'businessHours_legend' => '<strong>' . $GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] . $language['businessHours_legend'] . '</strong>',
            'contact_legend' => '<strong>' . $GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] . $language['contact_legend'] . '</strong>',
            'image_legend' => '<strong>' . $GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] . $language['image_legend'] . '</strong>',
            'linkWizard_legend' => '<strong>' . $GLOBALS['TL_LANG']['tl_c4g_data_type']['legend'] . $language['linkWizard_legend'] . '</strong>',

        ];

        $customFields = DataCustomFieldModel::findAll();
        foreach ($customFields as $customField) {
            if ($customField->frontendList === '1') {
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
