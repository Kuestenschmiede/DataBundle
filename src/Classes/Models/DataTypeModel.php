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

namespace con4gis\DataBundle\Classes\Models;


use Contao\Database;
use Contao\Model;

class DataTypeModel extends Model
{
    protected static $strTable = "tl_c4g_data_type";
    
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