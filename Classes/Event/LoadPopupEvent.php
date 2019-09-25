<?php
/*
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package    con4gis
 * @version    6
 * @author     con4gis contributors (see "authors.txt")
 * @license    LGPL-3.0-or-later
 * @copyright  Küstenschmiede GmbH Software & Design
 * @link       https://www.con4gis.org
 */
namespace con4gis\MapContentBundle\Classes\Event;


use Symfony\Component\EventDispatcher\Event;

class LoadPopupEvent extends Event
{
    const NAME = "mapcontent.popup.load";

    private $popup = '';
    private $showAddress = false;
    private $showBusinessTimes = false;
    private $elementData = [];

    /**
     * @param string $string
     * @return $this
     */
    public function addPopupString(string $string) {
        $this->popup .= $string;
        return $this;
    }

    /**
     * @return string
     */
    public function getPopupString() {
        return $this->popup;
    }

    /**
     * @return bool
     */
    public function isShowAddress(): bool
    {
        return $this->showAddress;
    }

    /**
     * @param bool $showAddress
     * @return LoadPopupEvent
     */
    public function setShowAddress(bool $showAddress = true): LoadPopupEvent
    {
        $this->showAddress = $showAddress;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowBusinessTimes(): bool
    {
        return $this->showBusinessTimes;
    }

    /**
     * @param bool $showBusinessTimes
     * @return LoadPopupEvent
     */
    public function setShowBusinessTimes(bool $showBusinessTimes = true): LoadPopupEvent
    {
        $this->showBusinessTimes = $showBusinessTimes;
        return $this;
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
    public function setElementData(array $elementData): LoadPopupEvent
    {
        $this->elementData = $elementData;
        return $this;
    }
}