<?php


namespace con4gis\MapContentBundle\Classes\Event;


use Symfony\Component\EventDispatcher\Event;

class LoadPropertiesEvent extends Event
{
    const NAME = "mapcontent.properties.load";

    private $properties =[];

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
     * @return LoadPopupEvent
     */
    public function setElementData(array $elementData): LoadPropertiesEvent
    {
        $this->elementData = $elementData;
        return $this;
    }
}