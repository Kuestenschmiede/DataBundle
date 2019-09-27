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


use con4gis\MapsBundle\Classes\Events\LoadFeatureFiltersEvent;
use con4gis\MapsBundle\Classes\Filter\FeatureFilter;
use Contao\Database;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class LoadFeatureFilterListener
{
    public function onLoadFeatureFilterCreateTagFilter(
        LoadFeatureFiltersEvent $event,
        $eventName,
        EventDispatcherInterface $eventDispatcher
    ) {
        $layerId = $event->getLayerId();
        $database = Database::getInstance();
        $filter = new FeatureFilter();
        $filter->setFieldName("tags");
        $tags = $database->prepare("SELECT * FROM tl_c4g_mapcontent_tag")
            ->execute()->fetchAllAssoc();
        foreach ($tags as $tag) {
            $filter->addFilterValue([
                "value" => $tag['id'],
                "translation" => $tag['name']
            ]);
        }
        $currentFilters = $event->getFilters();
        $event->setFilters(array_merge($currentFilters, [$filter]));
    }
}