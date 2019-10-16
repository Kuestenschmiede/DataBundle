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
use con4gis\MapsBundle\Resources\contao\models\C4gMapsModel;
use Contao\Database;
use Contao\Model\Collection;
use Contao\StringUtil;
use Contao\System;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LoadFeatureFilterListener
{
    public function onLoadFeatureFilter(
        LoadFeatureFiltersEvent $event,
        $eventName,
        EventDispatcherInterface $eventDispatcher
    ) {
        $layerId = $event->getProfileId();
        $layers = C4gMapsModel::findPublishedByPid($layerId);
        if ($layers !== null) {
            $typeIds = $this->loadTypeIds($layers);
            $typeIds = array_unique($typeIds);

            $typeModels = MapcontentTypeModel::findMultipleByIds($typeIds);
            $skipTypes = [];
            $filters = [];
            System::loadLanguageFile('tl_c4g_mapcontent_element');
            foreach ($typeModels as $model) {
                if (in_array($model->type, $skipTypes, true) === false) {
                    $skipTypes[] = $model->type;

                    $filterConfig = $GLOBALS['con4gis']['mapcontent_type_filters'][$model->type];
                    foreach ($filterConfig as $config) {
                        $filters[$GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$config][2]] =
                            $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$config . '_option'];
                    }

                    $tagModels = MapcontentTagModel::findMultipleByIds(StringUtil::deserialize($model->availableTags));
                    if ($tagModels !== null) {
                        foreach ($tagModels as $tagModel) {
                            $filters['Tags'][$tagModel->id] = $tagModel->name;
                        }
                    }
                }
            }
            if ($filters !== []) {
                foreach ($filters as $filterKey => $filter) {
                    $filter = array_unique($filter);
                    $filterObject = new FeatureFilter();
                    $filterObject->setFieldName($filterKey);
                    foreach ($filter as $key => $value) {
                        $filterObject->addFilterValue([
                            "value" => $key,
                            "translation" => $value
                        ]);
                    }
                    $currentFilters = $event->getFilters();
                    $event->setFilters(array_merge($currentFilters, [$filterObject]));
                }
            }
        }
    }

    private function loadTypeIds(Collection $mapCollection) : array {
        $typeIds = [];
        foreach ($mapCollection as $map) {
            $typeIds = array_merge($typeIds, StringUtil::deserialize($map->typeSelection));
            $children = C4gMapsModel::findPublishedByPid($map->id);
            if ($children !== null) {
                $typeIds = array_merge($typeIds, $this->loadTypeIds($children));
            }
        }
        return $typeIds;
    }
}