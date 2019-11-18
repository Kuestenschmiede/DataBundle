<?php
/**
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package   	con4gis
 * @version        6
 * @author  	    con4gis contributors (see "authors.txt")
 * @license 	    LGPL-3.0-or-later
 * @copyright 	Küstenschmiede GmbH Software & Design
 * @link              https://www.con4gis.org
 *
 */

namespace con4gis\MapContentBundle\Classes\Contao\Callbacks;


use con4gis\MapContentBundle\Resources\contao\models\MapcontentTagModel;
use con4gis\MapsBundle\Resources\contao\models\C4gMapLocstylesModel;
use Contao\Backend;
use Contao\StringUtil;
use Contao\System;

class MapcontentTypeCallback extends Backend
{
    
    public function getLocstyles()
    {
        $locstyles = C4gMapLocstylesModel::findAll();
        $arrLocstyles = [];
        foreach ($locstyles as $locstyle) {
            $arrLocstyles[$locstyle->id] = $locstyle->name;
        }
        return $arrLocstyles;
    }
    
    public function getAvailableTags()
    {
        $tags = MapcontentTagModel::findAll();
        $arrTags = [];
        foreach ($tags as $tag) {
            $arrTags[$tag->id] = $tag->name;
        }
        return $arrTags;
    }

    public function getLabel($arrRow){
        $label['name'] = $arrRow['name'];
        $label['type'] = $GLOBALS['TL_LANG']['mapcontent_types'][$arrRow['type']];
        $label['availableTags'] = '';
        foreach (StringUtil::deserialize($arrRow['availableTags']) as $tag) {
            $model = MapcontentTagModel::findByPk($tag);
            if ($label['availableTags'] === '') {
                $label['availableTags'] = $model->name;
            } else {
                $label['availableTags'] .= ', ' . $model->name;
            }
        }
        return $label;
    }

    public function getTypeOptions($dc) {
        $types = [];
        foreach ($GLOBALS['con4gis']['mapcontent_types'] as $type) {
            $types[$type] = $GLOBALS['TL_LANG']['mapcontent_types'][$type];
        }
        return $types;
    }

    public function loadAvailableFieldsOptions($dc)
    {
        System::loadLanguageFile('tl_c4g_mapcontent_element');
        return [
            'businessHours' => $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['businessHours'][0],
            'addressName' => $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressName'][0],
            'addressStreet' => $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressStreet'][0],
            'addressStreetNumber' => $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressStreetNumber'][0],
            'addressZip' => $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressZip'][0],
            'addressCity' => $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['addressCity'][0],
            'phone' => $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['phone'][0],
            'mobile' => $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['mobile'][0],
            'fax' => $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['fax'][0],
            'email' => $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['email'][0],
            'website' => $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['website'][0],
            'image' => $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['image'][0],
            'accessibility' => $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['accessibility'][0],
            'linkWizard' => $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['linkWizard'][0],
        ];
    }
}