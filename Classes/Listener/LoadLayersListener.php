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
use con4gis\MapContentBundle\Resources\contao\models\MapcontentElementModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentLocationModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTagModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use con4gis\MapsBundle\Classes\Events\LoadLayersEvent;
use con4gis\MapsBundle\Resources\contao\models\C4gMapsModel;
use con4gis\ProjectsBundle\Classes\Maps\C4GBrickMapFrontendParent;
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
    
//    /**
//     * Loads the available tags for the types and stores them in the types array.
//     * @param LoadLayersEvent $event
//     * @param $eventName
//     * @param EventDispatcherInterface $eventDispatcher
//     */
//    public function onLoadLayersLoadTags(
//        LoadLayersEvent $event,
//        $eventName,
//        EventDispatcherInterface $eventDispatcher
//    ) {
//        $types = $event->getAdditionalData()[''];
//        $resTagIds = [];
//        foreach ($types as $key => $type) {
//            // load tags and store them to the current type
//            $arrTagIds = unserialize($type['typeSelection']);
//            foreach ($arrTagIds as $tagId) {
//                if (!in_array($tagId, $resTagIds[$type['id']])) {
//                    $resTagIds[$type['id']][] = $tagId;
//                }
//            }
//            $objTags = MapcontentTagModel::findMultipleByIds($resTagIds);
//            $type['tags'] = $objTags->fetchAll();
//            $types[$key] = $type;
//        }
//        $addData = $event->getAdditionalData();
//        $addData['types'] = $types;
//        $event->setAdditionalData($addData);
//    }
    
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
            $elements = MapcontentElementModel::findBy('type', $typeId)->fetchAll();
            foreach ($elements as $key => $element) {
                $element['tags'] = unserialize($element['tags']);
                if (!$objLocations[$element['location']]) {
                    $objLocations[$element['location']] = MapcontentLocationModel::findByPk($element['location']);
                }
                $element['objLocation'] = $objLocations[$element['location']];
                $elements[$key] = $element;
            }
            $arrElements[$typeId] = $elements;
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
            $structureType = $fmClass->addMapStructureElementWithIdCalc(
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
                $objLocation = $typeElement['objLocation'];

                \System::loadLanguageFile('tl_c4g_mapcontent_element');
                $popupContent = '';
                $popupContent .= "<div class=\"name\">".$typeElement['name']."</div>";
                $popupContent .= "<div class=\"description\">".$typeElement['description']."</div>";

                $dispatcher = \Contao\System::getContainer()->get('event_dispatcher');
                $popupEvent = new LoadPopupEvent($type['type']);
                $popupEvent->setElementData($typeElement);
                $dispatcher->dispatch($popupEvent::NAME, $popupEvent);

                $popupContent .= $popupEvent->getPopupString();

                if ($popupEvent->isShowAddress() === true) {
                    $address = '';
                    if ($typeElement['addressName'] !== '') {
                        $address .= "<li>".$typeElement['addressName']."</li>";
                    }
                    if ($typeElement['addressStreet'] !== '') {
                        if ($typeElement['addressStreetNumber'] === 0) {
                            $address .= "<li>" . $typeElement['addressStreet'] . " " .
                                $typeElement['addressStreetNumber'] . "</li>";
                        } else {
                            $address .= "<li>" . $typeElement['addressStreet'] . "</li>";
                        }
                    }
                    if ($typeElement['addressZip'] !== '' && $typeElement['addressCity'] !== '') {
                        $address .= "<li>".$typeElement['addressZip']." ".$typeElement['addressCity']."</li>";
                    }
                    $popupContent .= "<ul class=\"address\">".$address."</ul>";
                }

                if ($popupEvent->isShowBusinessTimes() === true) {
                    $businessTimes = \StringUtil::deserialize($typeElement['businessHours']);
                    foreach ($businessTimes as $key => $time) {
                        $timeString[$key] = '';
                        if ($time['dayFrom'] !== '' && $time['timeFrom'] !== '' && $time['timeTo'] !== '') {
                            $timeString[$key] .= $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_reference'][$time['dayFrom']];
                            if ($time['dayTo'] !== $time['dayFrom']) {
                                $timeString[$key] .= " - " . $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_reference'][$time['dayTo']];
                            }
                            $timeString[$key] .= ": " . date('H:i', $time['timeFrom']) . " - " . date('H:i', $time['timeTo']);
                        }
                    }
                    if (isset($timeString) === true && count($timeString) > 0) {
                        $popupContent .= "<ul class=\"business_hours\">";
                        foreach ($timeString as $string) {
                            $popupContent .= "<li>" . $string . "</li>";
                        }
                        $popupContent .= "</ul>";
                    }
                }

                $contact = '';
                if ($popupEvent->isShowPhone() === true && $typeElement['phone']) {
                    $contact .= "<li>Tel.: ".$typeElement['phone']."</li>";
                }

                if ($popupEvent->isShowFax() === true && $typeElement['fax']) {
                    $contact .= "<li>Fax: ".$typeElement['fax']."</li>";
                }

                if ($popupEvent->isShowEmail() === true && $typeElement['email']) {
                    $contact .= "<li>Email: ".$typeElement['email']."</li>";
                }

                if ($contact !== '') {
                    $popupContent .= "<ul class=\"contact\">$contact</ul>";
                }

                $tagIds = \StringUtil::deserialize($typeElement['tags']);
                $tagModels = MapcontentTagModel::findMultipleByIds($tagIds);
                $tags = '';
                foreach ($tagModels as $model) {
                    if ($tags !== '') {
                        $tags .= ', ';
                    }
                    $tags .= $model->name;
                }

                $popupContent .= "<div class=\"tags\">".$tags."</div>";

                if ($objLocation->loctype === 'point') {
                    $content = $fmClass->addMapStructureContent(
                        $type['locstyle'],
                        $objLocation->geox,
                        $objLocation->geoy,
                        $popupContent,
                        $typeElement['name'],
                        $typeElement['name']
                    );
                } else {
                    $content = $fmClass->addMapStructureContentFromGeoJson(
                        $type['locstyle'],
                        $objLocation->geoJson,
                        $popupContent,
                        $typeElement['name'],
                        $typeElement['name']
                    );
                }
                $structureElement = $fmClass->addMapStructureElementWithIdCalc(
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
    
            $structureType = $fmClass->addMapStructureChilds($structureType, $structureElems);
    
            $structureTypes[] = $structureType;
        }
        $mapContentLayer = $fmClass->addMapStructureChilds($mapContentLayer, $structureTypes);
        $event->setLayerData($mapContentLayer);
    }
}