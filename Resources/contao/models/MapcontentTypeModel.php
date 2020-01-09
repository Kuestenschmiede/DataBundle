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
 *
 */

namespace con4gis\MapContentBundle\Resources\contao\models;


use Contao\Database;
use Contao\Model;

class MapcontentTypeModel extends Model
{
    protected static $strTable = "tl_c4g_mapcontent_type";
    
    public static function findBy($strColumn, $varValue, array $arrOptions = array())
    {
        if (!Database::getInstance()->tableExists(static::$strTable)) {
            return null;
        }
        return parent::findBy($strColumn, $varValue, $arrOptions);
    }
    
    public static function findAll(array $arrOptions = array())
    {
        if (!Database::getInstance()->tableExists(static::$strTable)) {
            return null;
        }
        return parent::findAll($arrOptions);
    }
    
    
}