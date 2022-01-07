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

class LoadPropertiesEvent extends Event
{
    const NAME = 'data.properties.load';

    private $properties = [];
    private $elementData = [];

    /**
     * @param array $properties
     * @return LoadPropertiesEvent
     */
    public function setProperties(array $properties): LoadPropertiesEvent
    {
        $this->properties = $properties;

        return $this;
    }
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @return array
     */
    public function getElementData(): array
    {
        return $this->elementData;
    }

    /**
     * @param array $elementData
     * @return LoadPropertiesEvent
     */
    public function setElementData(array $elementData): LoadPropertiesEvent
    {
        $this->elementData = $elementData;

        return $this;
    }
}
