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


use con4gis\CoreBundle\Resources\contao\models\C4gLogModel;
use con4gis\MapContentBundle\Classes\Event\LoadPopupEvent;
use con4gis\MapContentBundle\Classes\Event\LoadPropertiesEvent;
use con4gis\MapContentBundle\Classes\Popup\Popup;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentElementModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use con4gis\MapsBundle\Classes\Events\LoadLayersEvent;
use con4gis\MapsBundle\Classes\Services\LayerService;
use con4gis\MapsBundle\Resources\contao\models\C4gMapsModel;
use con4gis\ProjectsBundle\Classes\Maps\C4GBrickMapFrontendParent;
use Contao\Controller;
use Contao\FilesModel;
use Contao\StringUtil;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LoadLayersListener
{
    /**
     * LayerContentService constructor.
     * @param LayerService $layerService
     */
    public function __construct(LayerService $layerService)
    {
        $this->layerService = $layerService;
    }
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
            $elements = MapcontentElementModel::findPublishedBy('type', $typeId);
            if ($elements !== null) {
                $elements = $elements->fetchAll();
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
            $jsonFeatures = [];
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

                if (intval($typeElement['parentElement']) > 0) {
                    $toMerge = [
                        $typeElement
                    ];
                    while (intval($toMerge[0]['parentElement']) > 0) {
                        array_unshift($toMerge, MapcontentElementModel::findByPk([$toMerge[0]['parentElement']])->row());
                    }

                    $merge = [];
                    foreach ($toMerge as $merging) {
                        foreach ($merging as $k => $v) {
                            switch ($k) {
                                case 'businessHours':
                                case 'linkWizard':
                                    $array = StringUtil::deserialize($v);
                                    foreach ($array as $entry) {
                                        foreach ($entry as $item) {
                                            if ($item !== '') {
                                                $merge[$k] = $v;
                                                break 2;
                                            }
                                        }
                                    }
                                    break;
                                default:
                                    $merge[$k] = $v ? $v : $merge[$k];
                                    if ($merge[$k] === null) {
                                        $merge[$k] = '';
                                    }
                            }
                        }
                    }
                    if (!empty($merge)) {
                        $typeElement = $merge;
                    }
                }

                $popup = new Popup();
                \System::loadLanguageFile('tl_c4g_mapcontent_element');
                $popup->addName($typeElement['name']);

                $dispatcher = \Contao\System::getContainer()->get('event_dispatcher');

                if ($typeElement['addressName'] !== '' ||
                    $typeElement['addressStreet'] !== '' ||
                    $typeElement['addressStreetNumber'] !== '0' ||
                    $typeElement['addressZip'] !== '' ||
                    $typeElement['addressCity'] !== ''
                ) {
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
                    if ($fileModel !== null) {
                        $popup->addImage($fileModel->path, $typeElement['imageMaxHeight'], $typeElement['imageMaxWidth']);
                    } else {
                        C4gLogModel::addLogEntry('map-content', 'Popupimage of element '.$typeElement['id'].' with uuid '.$typeElement['image'].' not found.');
                    }
                }

                $businessTimes = \StringUtil::deserialize($typeElement['businessHours']);
                if (!empty($businessTimes) || $typeElement['businessHoursAdditionalInfo'] !== '') {
                    $timeString = [];
                    $showBusinessTimes = false;
                    foreach ($businessTimes as $key => $time) {
                        $timeString[$key] = '';
                        if ($time['dayFrom'] !== '' && $time['timeFrom'] !== '' && $time['timeTo'] !== '') {
                            $timeString[$key] .= $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_reference'][$time['dayFrom']];
                            if ($time['dayTo'] !== $time['dayFrom'] && $time['dayTo'] !== '') {
                                if (abs(intval($time['dayTo']) - intval($time['dayFrom'])) > 1) {
                                    $join = $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_join']['to'];
                                } else {
                                    $join = $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_join']['and'];
                                }

                                $timeString[$key] .= " $join " . $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['day_reference'][$time['dayTo']];
                            }
                            $timeString[$key] .= ": " . date('H:i', $time['timeFrom']) .
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['timeCaption'] .
                                " - " . date('H:i', $time['timeTo']) .
                                $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['timeCaption'];
                            $showBusinessTimes = true;
                        }
                    }
                    if ($typeElement['businessHoursAdditionalInfo'] !== '') {
                        $timeString[] = $typeElement['businessHoursAdditionalInfo'];
                        $showBusinessTimes = true;
                    }
                    if ($showBusinessTimes === true) {
                        $bH = [];
                        $entries = [];
                        foreach ($timeString as $string) {
                            $explode = explode(': ', $string);
                            $key = $explode[0];
                            if (isset($bH[$key]) === true) {
                                $bH[$key] .= ', '.$explode[1];
                            } else {
                                $bH[$key] = $explode[1];
                            }
                        }
                        foreach ($bH as $k => $v) {
                            if (!empty($v)) {
                                $entries[] = $k.': '.$v;
                            } else {
                                $entries[] = $k;
                            }
                        }
                        $popup->addBusinessHours($entries, $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['businessHours'][0].':');
                    }
                }

                $popup->addContactInfo(strval($typeElement['phone']), strval($typeElement['mobile']), strval($typeElement['fax']), strval($typeElement['email']), strval($typeElement['website']),
                    $GLOBALS['TL_LANG']['tl_c4g_mapcontent_element']['contactData'][0].':', 'contact', true);
                $propertiesEvent = new LoadPropertiesEvent();
                $propertiesEvent->setElementData($typeElement);
                $dispatcher->dispatch($propertiesEvent::NAME, $propertiesEvent);
                
                $properties = $propertiesEvent->getProperties();

                if ($typeElement['linkWizard']) {
                    $popup->addLinks(StringUtil::deserialize($typeElement['linkWizard']));
                }
                $popup->addDescription(strval($typeElement['description']));

                $label = $type['showLabels'] === '1' ? $typeElement['name'] : '';

                $stringClass = $GLOBALS['con4gis']['stringClass'];
                $popupInfo   = $stringClass::toHtml5($popup->getPopupString());
                $popupInfo   = Controller::replaceInsertTags($popupInfo, false);
                $popupInfo   = str_replace(['{{request_token}}', '[{]', '[}]'], [REQUEST_TOKEN, '{{', '}}'], $popupInfo);
                $popupInfo   = Controller::replaceDynamicScriptTags($popupInfo);
                $objComments = new \Comments();
                $popupInfo   = $objComments->parseBbCode($popupInfo);
                $properties['popup'] = [
                    'content' => $popupInfo,
                    'routing_link' => "1",
                    'async' => false,
                ];
                $properties['title'] = $typeElement['name'];
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

                    $geoJSON = $this->layerService->createGeoJSONFeature($properties, $typeElement['geox'], $typeElement['geoy']);
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
                    $geoJSON = $this->layerService->createGeoJSONFeature($properties,null, null, $typeElement['geoJson']);
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
                $geoJSON['properties']['id'] = $structureElement['id'];
                $jsonFeatures[] = $geoJSON;
                $structureElems[] = $structureElement;
            }
    
            $structureType = $fmClass->createMapStructureChilds($structureType, $structureElems);
            $globalJSON = [
                "type"          => "FeatureCollection",
                "features"      => $jsonFeatures,
                "properties"    => [
                    "projection" => "EPSG:4326"
                ]
            ];
            $content = $structureType['childs'][0]['content'][0];
            $content['data'] = $globalJSON;
            $content['combinedJSON'] = true;
            $structureType['content'][0] = $content;
            $structureType['childs'] = [];
            $structureType['hasChilds'] = false;
            $structureType['childsCount'] = 0;

            $structureTypes[] = $structureType;
        }
        $mapContentLayer = $fmClass->createMapStructureChilds($mapContentLayer, $structureTypes);
        $event->setLayerData($mapContentLayer);
    }
}