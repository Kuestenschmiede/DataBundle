<?php

namespace con4gis\DataBundle\Classes\Event;

use Symfony\Component\EventDispatcher\Event;

class LoadAdditionalListDataEvent extends Event
{
    const NAME = 'data.additionalListData.load';

    private $row;

    public function __construct(array $row) {
        $this->row = $row;
    }

    public function getRow(): array
    {
        return $this->row;
    }

    public function get(string $column, $default = null) {
        return $this->row[$column] ?: $default;
    }

    public function set(string $column, $value) {
        $this->row[$column] = $value;
    }
}
