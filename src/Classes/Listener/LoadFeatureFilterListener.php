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

namespace con4gis\DataBundle\Classes\Listener;

use con4gis\MapsBundle\Classes\Events\LoadFeatureFiltersEvent;
use con4gis\MapsBundle\Classes\Filter\FeatureFilter;
use con4gis\MapsBundle\Classes\Services\FilterService;
use con4gis\MapsBundle\Resources\contao\models\C4gMapProfilesModel;
use con4gis\MapsBundle\Resources\contao\models\C4gMapsModel;
use Contao\Database;
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
        $mapId = $event->getProfileId();
        $modelMaps = C4gMapsModel::findById($mapId); //ToDo
        $modelProfile = C4gMapProfilesModel::findById($modelMaps->profile);
        $currentFilters = $event->getFilters();
        $strSelect = 'SELECT * FROM tl_c4g_data_custom_field WHERE published = "1" AND type="multicheckbox" AND frontendFilter =1 ';
        $customFields = $this->Database->execute($strSelect)->fetchAllAssoc();
        if ((int) $modelProfile->filterTypeData != 0) {
            foreach ($customFields as $customField) {
                $filterObject = new FeatureFilter();
                $filterObject->setFieldName($customField['frontendName'] ?: $customField['name']);
                foreach (unserialize($customField['options']) as $option) {
                    $filterObject->addFilterValue([
                        'identifier' => $option['key'],
                        'translation' => $option['value'],
                    ]);
                }
                $currentFilters = array_merge($currentFilters, [$filterObject]);
            }
        } else {
            foreach ($customFields as $customField) {
                foreach (unserialize($customField['options']) as $option) {
                    $filterObject = new FeatureFilter();
                    $filterObject->setFieldName($option['value']);
                    $filterObject->addFilterValue([
                        'identifier' => $option['key'],
                        'translation' => $option['value'],
                    ]);
                    $currentFilters = array_merge($currentFilters, [$filterObject]);
                }
            }
        }

        $event->setFilters($currentFilters);
    }
}
