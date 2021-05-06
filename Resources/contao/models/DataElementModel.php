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

namespace con4gis\DataBundle\Resources\contao\models;


use Contao\Database;
use Contao\Model;
use \Throwable;

class DataElementModel extends Model
{
    protected static $strTable = "tl_c4g_data_element";

    public static function findAllPublished() {
        $database = Database::getInstance();
        $stmt = $database->prepare("SELECT * FROM tl_c4g_data_element "."
        WHERE (publishFrom >= ? OR publishFrom = '') AND (publishTo < ? OR publishTo = '')");
        try {
            return static::createCollectionFromDbResult($stmt->execute(time(), time()), static::$strTable);
        } catch (Throwable $throwable) {
            return null;
        }
    }

    public static function findPublishedBy($field, $value) {
        $database = Database::getInstance();
        $stmt = $database->prepare("SELECT * FROM tl_c4g_data_element "."
        WHERE ($field = ?) AND (publishFrom >= ? OR publishFrom = '') AND (publishTo < ? OR publishTo = '')");
        try {
            return static::createCollectionFromDbResult($stmt->execute($value, time(), time()), static::$strTable);
        } catch (Throwable $throwable) {
            return null;
        }
    }
    public static function findRealPublishedBy($field, $value) {
        $database = Database::getInstance();
        $stmt = $database->prepare("SELECT * FROM tl_c4g_data_element "."
        WHERE ($field = ?) AND published = '1'");
        try {
            return static::createCollectionFromDbResult($stmt->execute($value), static::$strTable);
        } catch (Throwable $throwable) {
            return null;
        }
    }

    public function string($property) : string {
        return strval($this->$property);
    }
}