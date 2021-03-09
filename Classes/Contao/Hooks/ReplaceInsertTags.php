<?php
/*
  * This file is part of con4gis,
  * the gis-kit for Contao CMS.
  *
  * @package   	con4gis
  * @version    7
  * @author  	con4gis contributors (see "authors.txt")
  * @license 	LGPL-3.0-or-later
  * @copyright 	Küstenschmiede GmbH Software & Design
  * @link       https://www.con4gis.org
  */

namespace con4gis\DataBundle\Classes\Contao\Hooks;

use Contao\Database;

/**
 * Class ReplaceInsertTags
 * @package MapsProjectBundle
 */
class ReplaceInsertTags
{
    /**
     * Instanz von \Contao\Database
     * @var Database|null
     */
    protected $db = null;

    /**
     * ReplaceInsertTags constructor.
     * @param null $db
     */
    public function __construct($db = null)
    {
        if ($db !== null) {
            $this->db = $db;
        } else {
            $this->db = \Contao\Database::getInstance();
        }
    }

    /**
     * @param $tag
     * @return bool
     */
    public function replaceTag($strTag)
    {
        if ($strTag) {
            $arrSplit = explode('::', $strTag);
        }

        if ($arrSplit && (($arrSplit[0] == 'c4gdata')) && isset($arrSplit[1])) {
            $id = $arrSplit[1];
            $fieldName = $arrSplit[2];

            if ($id && $fieldName) {
                $table = 'tl_c4g_data_element';

                $query = $this->db->prepare("SELECT * FROM $table WHERE id=?")
                    ->limit(1)
                    ->execute($id, 1);

                if ($query->numRows) {
                    switch ($fieldName) {
                        case 'image':
                            $file = \Contao\FilesModel::findByUuid($query->$fieldName);
                            if ($file) {
                                $image = \Contao\Image::get($file->path, auto, 40);

                                return '<div class="ce_image block" id="c4g_data_image_' . $id . '">' .
                                    '<figure class="image_container" itemscope="" itemtype="http://schema.org/ImageObject">' .
                                    '<img class="con4gis_application_logo" src="' . $image . '" itemprop="image" alt="' . $query->name . ' Image">' .
                                    '</figure>' .
                                    '</div>';
                            }

                            break;
                        default:
                            return $query->$fieldName;
                    }
                } else {
                    return '';
                }
            }
        }

        return false;
    }
}
