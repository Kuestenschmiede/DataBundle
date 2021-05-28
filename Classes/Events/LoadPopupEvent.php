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

use con4gis\DataBundle\Classes\Popup\Popup;
use Symfony\Component\EventDispatcher\Event;

class LoadPopupEvent extends Event
{
    const NAME = 'data.popup.load';

    private $popup;
    private $type = '';
    private $showAddress = false;
    private $showPhone = false;
    private $showFax = false;
    private $showEmail = false;
    private $showBusinessTimes = false;
    private $elementData = [];

    public function __construct(string $type, Popup $popup)
    {
        $this->type = $type;
        $this->popup = $popup;
    }

    /**
     * @return Popup
     */
    public function getPopup(): Popup
    {
        return $this->popup;
    }

    /**
     * @param Popup $popup
     * @return LoadPopupEvent
     */
    public function setPopup(Popup $popup): LoadPopupEvent
    {
        $this->popup = $popup;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
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
    public function isShowPhone(): bool
    {
        return $this->showPhone;
    }

    /**
     * @param bool $showPhone
     * @return LoadPopupEvent
     */
    public function setShowPhone(bool $showPhone = true): LoadPopupEvent
    {
        $this->showPhone = $showPhone;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowFax(): bool
    {
        return $this->showFax;
    }

    /**
     * @param bool $showFax
     * @return LoadPopupEvent
     */
    public function setShowFax(bool $showFax = true): LoadPopupEvent
    {
        $this->showFax = $showFax;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowEmail(): bool
    {
        return $this->showEmail;
    }

    /**
     * @param bool $showEmail
     * @return LoadPopupEvent
     */
    public function setShowEmail(bool $showEmail = true): LoadPopupEvent
    {
        $this->showEmail = $showEmail;

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
