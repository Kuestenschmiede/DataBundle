<?php
/**
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package   	con4gis
 * @version        6
 * @author  	    con4gis contributors (see "authors.txt")
 * @license 	    LGPL-3.0-or-later
 * @copyright 	Küstenschmiede GmbH Software & Design
 * @link              https://www.con4gis.org
 *
 */
namespace con4gis\MapContentBundle\Classes\Listener;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTagModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use con4gis\MapsBundle\Classes\Events\LoadFeatureFiltersEvent;
use con4gis\MapsBundle\Classes\Filter\FeatureFilter;
use con4gis\MapsBundle\Classes\Services\FilterService;
use con4gis\MapsBundle\Resources\contao\models\C4gMapsModel;
use Contao\Database;
use Contao\Model\Collection;
use Contao\StringUtil;
use Contao\System;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
class LoadFeatureFilterListener
{
    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
        $this->Database = Database::getInstance();
    }
    public function onLoadFeatureFilters(
        LoadFeatureFiltersEvent $event,
        $eventName,
        EventDispatcherInterface $eventDispatcher
    ) {
        $currentFilters = $event->getFilters();
        $strSelect = 'SELECT * FROM tl_c4g_mapcontent_custom_field WHERE type="multicheckbox" AND frontendFilter =1 ';
        $customFields = $this->Database->execute($strSelect)->fetchAllAssoc();

        foreach ($customFields as $customField) {
            $filterObject = new FeatureFilter();
            $filterObject->setFieldName($customField['frontendName'] ?: $customField['name']);
            foreach(unserialize($customField["options"]) as $option) {
                $filterObject->addFilterValue([
                    "identifier" => $option["key"],
                    "translation" => $option["value"]
                ]);
            }
            $currentFilters = array_merge($currentFilters, [$filterObject]);
        }
        $event->setFilters($currentFilters);
    }
}