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


use con4gis\MapContentBundle\Classes\Event\LoadPopupEvent;
use con4gis\MapContentBundle\Classes\Event\LoadPropertiesEvent;
use con4gis\MapContentBundle\Classes\Popup\Popup;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentElementModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentLocationModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTagModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use con4gis\MapsBundle\Classes\Events\LoadLayersEvent;
use con4gis\MapsBundle\Resources\contao\models\C4gMapsModel;
use con4gis\ProjectsBundle\Classes\Maps\C4GBrickMapFrontendParent;
use Contao\FilesModel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LoadLayersListener
{
    public function onLoadLayersLoadTypes(
        LoadLayersEvent $event,
        $eventName,
        EventDispatcherInterface $eventDispatcher
    ) {
        $mapContentLayer = $event->getLayerData();
        if (!$mapContentLayer['type'] === "mpCntnt") {
            return;
        }
        $objMapContent = C4gMapsModel::findByPk($mapContentLayer['id']);
        $selectedTypes = unserialize($objMapContent->typeSelection);
        if (!$selectedTypes) {
            return;
        }
        $objSelectedTypes = MapcontentTypeModel::findMultipleByIds($selectedTypes);
        $arrTypes = $objSelectedTypes->fetchAll();
        $addData = $event->getAdditionalData();
        $addData['types'] = $arrTypes;
        $addData['typeIds'] = $selectedTypes;
        $addData['mapContentLayer'] = $mapContentLayer;
        $event->setAdditionalData($addData);
    }

    public function onLoadLayersLoadElements(
        LoadLayersEvent $event,
        $eventName,
        EventDispatcherInterface $eventDispatcher
    ) {
        $mapContentLayer = $event->getLayerData();
        if (!$mapContentLayer['type'] === "mpCntnt") {
            return;
        }
        $objLocations = [];
        $types = $event->getAdditionalData()['types'];
        $typeIds = $event->getAdditionalData()['typeIds'];
        $arrElements = [];
        foreach ($typeIds as $typeId) {
            $elements = MapcontentElementModel::findBy('type', $typeId);
            if ($elements !== null) {
                $elements = $elements->fetchAll();
                foreach ($elements as $key => $element) {
                    $element['tags'] = unserialize($element['tags']);
                    $elements[$key] = $element;
                }
                $arrElements[$typeId] = $elements;
            }
        }
        
        $addData = $event->getAdditionalData();
        $addData['types'] = $types;
        $addData['elements'] = $arrElements;
        $event->setAdditionalData($addData);
    }
    
    public function onLoadLayersCreateMapStructures(
        LoadLayersEvent $event,
        $eventName,
        EventDispatcherInterface $eventDispatcher
    ) {
        $mapContentLayer = $event->getLayerData();
        if (!($mapContentLayer['type'] === "mpCntnt")) {
            return;
        }
        $fmClass = new C4GBrickMapFrontendParent();
        $addData = $event->getAdditionalData();
        $structureTypes = [];
        $types = $addData['types'];
        $elements = $addData['elements'];
        foreach ($types as $type) {
            $structureElems = [];
            $structureType = $fmClass->createMapStructureElementWithIdCalc(
                $type['id'],
                $mapContentLayer['id'],
                $mapContentLayer['pid'],
                419,
                'GeoJSON',
                $type['name'],
                $type['name'],
                true,
                $mapContentLayer['hide']
            );
            
            foreach ($elements[$type['id']] as $typeElement) {
                $popup = new Popup();
                \System::loadLanguageFile('tl_c4g_mapcontent_element');
                $popup->addName($typeElement['name']);

                $dispatcher = \Contao\System::getContainer()->get('event_dispatcher');
                $popupEvent = new LoadPopupEvent($type['type'], $popup);
                $popupEvent->setElementData($typeElement);
                $dispatcher->dispatch($popupEvent::NAME, $popupEvent);

                if ($popupEvent->isShowAddress() === true) {
                    $address = [];
                    if ($typeElement['addressName'] !== '') {
                        $address[] = $typeElement['addressName'];
                    }
                    if ($typeElement['addressStreet'] !== '') {
                        if ($typeElement['addressStreetNumber'] !== '0') {
                            $address[] = $typeElement['addressStreet'] . " " .
                                $typeElement['addressStreetNumber'];
                        } else {
                            $address[] = $typeElement['addressStreet'];
                        }
                    }
                    if ($typeElement['addressZip'] !== '' && $typeElement['addressCity'] !== '') {
                        $address[] = $typeElement['addressZip'] . " " . $typeElement['addressCity'];
                    }
                    $popup->addAddress($address);
                }

                if ($typeElement['image'] !== '' && is_string($typeElement['image'])) {
                    $fileModel = FilesModel::findByUuid($typeElement['image']);
                    $popup->addImage($fileModel->path, $typeElement['imageMaxHeight'], $typeElement['imageMaxWidth']);
                }

                if ($popupEvent->isShowBusinessTimes() === true) {
                    $timeString = [];
                    $businessTimes = \StringUtil::deserialize($typeElement['businessHours']);
                    $showBusinessTimes = false;
                    foreach ($businessTimes as $key => $time) {
                        $timeString[$key] = '';
                        if ($time['dayFrom'] !== '' && $time['timeFrom'] !== '' && $time['timeTo'] !== '') {
                            $timeString[$key] .= $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_reference'][$time['dayFrom']];
                            if ($time['dayTo'] !== $time['dayFrom']) {
                                if (abs(intval($time['dayTo']) - intval($time['dayFrom'])) > 1) {
                                    $join = $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_join']['to'];
                                } else {
                                    $join = $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_join']['and'];
                                }

                                $timeString[$key] .= " $join " . $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_reference'][$time['dayTo']];
                            }
                            $timeString[$key] .= ": " . date('H:i', $time['timeFrom']) . " - " . date('H:i', $time['timeTo']);
                            $showBusinessTimes = true;
                        }
                    }
                    if ($typeElement['businessHoursAdditionalInfo'] !== '') {
                        $timeString[] = $typeElement['businessHoursAdditionalInfo'];
                        $showBusinessTimes = true;
                    }
                    if ($showBusinessTimes === true) {
                        $popup->addBusinessHours($timeString);
                    }
                }

                $popup->addContactInfo($typeElement['phone'], $typeElement['mobile'], $typeElement['fax'], $typeElement['email'], $typeElement['website']);
                $propertiesEvent = new LoadPropertiesEvent();
                $propertiesEvent->setElementData($typeElement);
                $dispatcher->dispatch($propertiesEvent::NAME, $propertiesEvent);
                
                $properties = $propertiesEvent->getProperties();
                $tagIds = \StringUtil::deserialize($typeElement['tags']);
                $tagModels = MapcontentTagModel::findMultipleByIds($tagIds);
                $tags = [];
                foreach ($tagModels as $model) {
                    $tags[] = $model->name;
                }

                $popup->addTags($tags);
                $popup->addDescription(strval($typeElement['description']));

                $label = $type['showLabels'] === '1' ? $typeElement['name'] : '';

                if ($typeElement['loctype'] === 'point') {
                    $content = $fmClass->createMapStructureContent(
                        $type['locstyle'],
                        $typeElement['geox'],
                        $typeElement['geoy'],
                        $popup->getPopupString(),
                        $label,
                        $typeElement['name'],
                        null,
                        null,
                        60000,
                        $properties
                    );
                } else {
                    $content = $fmClass->createMapStructureContentFromGeoJson(
                        $type['locstyle'],
                        $typeElement['geoJson'],
                        $popup->getPopupString(),
                        $typeElement['name'],
                        $typeElement['name'],
                        null,
                        null,
                        60000,
                        $properties
                    );
                }
                $structureElement = $fmClass->createMapStructureElementWithIdCalc(
                    $typeElement['id'],
                    $structureType['id'],
                    $structureType['pid'],
                    421,
                    'GeoJSON',
                    $typeElement['name'],
                    $typeElement['name'],
                    true,
                    $structureType['hide'],
                    $content
                );
                
                
                $structureElems[] = $structureElement;
            }
    
            $structureType = $fmClass->createMapStructureChilds($structureType, $structureElems);
    
            $structureTypes[] = $structureType;
        }
        $mapContentLayer = $fmClass->createMapStructureChilds($mapContentLayer, $structureTypes);
        $event->setLayerData($mapContentLayer);
    }
}