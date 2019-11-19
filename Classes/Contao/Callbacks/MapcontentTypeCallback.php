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
        $language = $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'];
        return [
            'businessHours' => $language['businessHours'][0] .
                " - ".$language['businessHours'][1],
            'addressName' => $language['addressName'][0] .
                " - ".$language['addressName'][1],
            'addressStreet' => $language['addressStreet'][0] .
                " - ".$language['addressStreet'][1],
            'addressStreetNumber' => $language['addressStreetNumber'][0],
            'addressZip' => $language['addressZip'][0],
            'addressCity' => $language['addressCity'][0],
            'phone' => $language['phone'][0],
            'mobile' => $language['mobile'][0],
            'fax' => $language['fax'][0],
            'email' => $language['email'][0],
            'website' => $language['website'][0],
            'image' => $language['image'][0] .
                " - ".$language['image'][1],
            'accessibility' => $language['accessibility'][0] .
                " - ".$language['accessibility'][1],
            'linkWizard' => $language['linkWizard'][0] .
                " - ".$language['linkWizard'][1],
            'osmId' => $language['osmId'][0] .
                " - ".$language['osmId'][1],
        ];
    }
}