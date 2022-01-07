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

namespace con4gis\DataBundle\Classes\Events;

use Symfony\Contracts\EventDispatcher\Event;

class LoadAdditionalListDataEvent extends Event
{
    const NAME = 'data.additionalListData.load';

    private $row;

    public function __construct(array $row)
    {
        $this->row = $row;
    }

    public function getRow(): array
    {
        return $this->row;
    }

    public function get(string $column, $default = [])
    {
        return $this->row[$column] ?: $default;
    }

    public function set(string $column, $value)
    {
        $this->row[$column] = $value;
    }
}
