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


use con4gis\CoreBundle\Resources\contao\classes\C4GUtils;
use con4gis\CoreBundle\Resources\contao\models\C4gLogModel;
use con4gis\MapContentBundle\Classes\Event\LoadPropertiesEvent;
use con4gis\MapContentBundle\Classes\Popup\Popup;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentCustomFieldModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentElementModel;
use con4gis\MapContentBundle\Resources\contao\models\MapcontentTypeModel;
use con4gis\MapsBundle\Classes\Events\LoadLayersEvent;
use con4gis\MapsBundle\Classes\Services\LayerService;
use con4gis\MapsBundle\Resources\contao\models\C4gMapsModel;
use con4gis\ProjectsBundle\Classes\Maps\C4GBrickMapFrontendParent;
use Contao\Controller;
use Contao\Database;
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
        $this->Database = Database::getInstance();
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
            $availableFields = unserialize($type['availableFields']);
            if ($availableFields) {
                $strSelect = 'SELECT * FROM tl_c4g_mapcontent_custom_field WHERE type="multicheckbox" AND frontendFilter =1 AND alias IN(';
                foreach($availableFields as $availableField) {
                    $strSelect .= '"'. $availableField . '",';
                }
                $strSelect = substr($strSelect, 0, strlen($strSelect)-1) . ')';
                $combineFields = $this->Database->execute($strSelect)->fetchAllAssoc();
            }
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

                $popup->addEntry(strval($typeElement['name']), 'name');
                if (strval($typeElement['description']) !== '') {
                    $popup->addEntry(strval($typeElement['description']), 'description');
                }
                $addressIsSet = false;

                foreach ($availableFields as $fieldKey => $availableField) {
                    if (($availableField === 'addressName' ||
                            $availableField === 'addressStreet' ||
                            $availableField === 'addressStreetNumber' ||
                            $availableField === 'addressZip' ||
                            $availableField === 'addressCity')
                    ) {
                        if ($addressIsSet === false) {
                            $addressIsSet = true;
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
                            $popup->addEntry(implode(', ', $address), 'address');
                        }
                    }

                    elseif ($availableField === 'image') {
                        if (is_string($typeElement['image']) === true)
                        {
                            $fileModel = FilesModel::findByUuid($typeElement['image']);
                            if ($fileModel !== null) {
                                $popup->addImageEntry($fileModel->path, $typeElement['imageMaxHeight'], $typeElement['imageMaxWidth'], 'image', strval($typeElement['imageLink']));
                            } else {
                                C4gLogModel::addLogEntry('map-content', 'Popupimage of element ' . $typeElement['id'] . ' with uuid ' . $typeElement['image'] . ' not found.');
                            }
                        }
                    }

                    elseif ($availableField === 'businessHours') {
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
                            if ($showBusinessTimes === true) {
                                $bH = [];
                                $entries = [];
                                foreach ($timeString as $string) {
                                    $explode = explode(': ', $string);
                                    $key = $explode[0];
                                    if (isset($bH[$key]) === true) {
                                        $bH[$key] .= ', ' . $explode[1];
                                    } else {
                                        $bH[$key] = $explode[1];
                                    }
                                }
                                foreach ($bH as $k => $v) {
                                    if (!empty($v)) {
                                        $entries[] = $k . ': ' . $v;
                                    } else {
                                        $entries[] = $k;
                                    }
                                }
                                foreach ($entries as $entry) {
                                    $popup->addEntry(strval($entry), 'businessHours');
                                }
                            }
                            if ($typeElement['businessHoursAdditionalInfo'] !== '') {
                                $popup->addEntry(strval($typeElement['businessHoursAdditionalInfo']), 'businessHours');
                            }
                        }
                    }

                    elseif ($availableField === 'linkWizard') {
                        foreach (StringUtil::deserialize($typeElement['linkWizard']) as $link) {
                            $popup->addLinkEntry(strval($link['linkTitle']), 'link', strval($link['linkHref']), $link['linkNewTab']);
                        }
                    } elseif ($availableField === 'phone') {
                        if ($typeElement['phone'] !== '') {
                            $list['linkHref'] = 'tel:' . $typeElement['phone'];
                            $list['linkTitle'] = "Tel.: " . $typeElement['phone'];
                            $popup->addLinkEntry(strval($list['linkTitle']), 'phone', strval($list['linkHref']));
                        }
                    } elseif ($availableField === 'mobile') {
                        if ($typeElement['mobile'] !== '') {
                            $list['linkHref'] = 'tel:' . $typeElement['mobile'];
                            $list['linkTitle'] = "Mobil: " . $typeElement['mobile'];
                            $popup->addLinkEntry(strval($list['linkTitle']), 'mobile', strval($list['linkHref']));
                        }
                    } elseif ($availableField === 'fax') {
                        if ($typeElement['fax'] !== '') {
                            $list['linkHref'] = '';
                            $list['linkTitle'] = $typeElement['fax'];
                            $popup->addEntry(strval($list['linkTitle']), 'fax');
                        }
                    } elseif ($availableField === 'email') {
                        if ($typeElement['email'] !== '') {
                            $list['linkHref'] = 'mailto:' . $typeElement['email'];
                            $list['linkTitle'] = "Email: " . $typeElement['email'];
                            $popup->addLinkEntry(strval($list['linkTitle']), 'email', strval($list['linkHref']));
                        }
                    } elseif ($availableField === 'website') {
                        if (!C4GUtils::startsWith($typeElement['website'], 'http')) {
                            $list['linkHref'] = 'http://'.$typeElement['website'];
                        } else {
                            $list['linkHref'] = $typeElement['website'];
                        }
                        $list['linkHref'] = $typeElement['website'];
                        $list['linkTitle'] = $typeElement['website'];
                        $popup->addLinkEntry(strval($list['linkTitle']), 'website', strval($list['linkHref']));
                    }

                    else {
                        $model = MapcontentCustomFieldModel::findBy('alias', $availableField);
                        if ($model !== null) {
                            if (strval($model->frontendPopup) === '1') {
                                if (strval($model->type) === 'legend' && (strval($model->frontendName) !== '' || strval($model->name) !== '')) {
                                    $i = $fieldKey + 1;
                                    while ($i < count($availableFields)) {
                                        $legendModel = MapcontentCustomFieldModel::findBy('alias', $availableFields[$i]);
                                        if ($legendModel !== null && $legendModel->type === 'legend') {
                                            break;
                                        } if (C4GUtils::endsWith($availableFields[$i], '_legend') === true) {
                                            break;
                                        }
                                        $i += 1;
                                    }
                                    $n = $fieldKey + 1;
                                    $show = false;
                                    while ($i > $n) {
                                        if (strval($typeElement[$availableFields[$n]]) !== ''
                                            && intval($typeElement[$availableFields[$n]]) !== 0) {
                                            $show = true;
                                        }
                                        $n += 1;
                                    }
                                    if ($show === true) {
                                        $popup->addEntry(strval($model->frontendName ?: $model->name), 'legend');
                                    }
                                } elseif (strval($model->type) !== 'legend') {
                                    switch ($model->type) {
                                        case 'select':
                                        case 'multicheckbox':
                                            $options = StringUtil::deserialize($model->options);
                                            if (is_array($options)) {
                                                foreach ($options as $option) {
                                                    if ($option['key'] === $typeElement[$availableField]) {
                                                        $popup->addEntry($option['value'], $availableField);
                                                        break;
                                                    }
                                                }
                                            }
                                            break;
                                        case 'datepicker':
                                            $popup->addEntry(date('d.m.Y', $typeElement[$availableField]), $availableField);
                                            break;
                                        default:
                                            $popup->addEntry(strval($typeElement[$availableField]), $availableField);
                                    }
                                }
                            }
                        } else {
                            if (C4GUtils::endsWith($availableField, '_legend') === true) {
                                switch ($availableField) {
                                    case 'address_legend':
                                    case 'image_legend':
                                    case 'linkWizard_legend':
                                    case 'publish_legend':
                                        break;
                                    default:
                                        $i = $fieldKey + 1;
                                        while ($i < count($availableFields)) {
                                            $legendModel = MapcontentCustomFieldModel::findBy('alias', $availableFields[$i]);
                                            if ($legendModel !== null && $legendModel->type === 'legend') {
                                                break;
                                            } if (C4GUtils::endsWith($availableFields[$i], '_legend') === true) {
                                                break;
                                            }
                                            $i += 1;
                                        }
                                        $n = $fieldKey + 1;
                                        $show = false;
                                        while ($i > $n) {
                                            if (strval($typeElement[$availableFields[$n]]) !== ''
                                            && intval($typeElement[$availableFields[$n]]) !== 0) {
                                                $show = true;
                                            }
                                            $n += 1;
                                        }
                                        if ($show === true) {
                                            if (strval($GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$availableField]) !== '') {
                                                $popup->addEntry(
                                                    strval($GLOBALS['TL_LANG']['tl_c4g_mapcontent_element'][$availableField]),
                                                    $availableField
                                                );
                                            }
                                        }
                                        break;
                                }
                            } elseif (strval($typeElement[$availableField]) !== '' &&
                                intval($typeElement[$availableField]) !== 0) {
                                $popup->addEntry(
                                    strval($typeElement[$availableField]),
                                    $availableField);
                            }
                        }
                    }
                }

                $propertiesEvent = new LoadPropertiesEvent();
                $propertiesEvent->setElementData($typeElement);

                $dispatcher = \Contao\System::getContainer()->get('event_dispatcher');
                $dispatcher->dispatch($propertiesEvent::NAME, $propertiesEvent);
                
                $properties = $propertiesEvent->getProperties();

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
                if ($availableFields && $combineFields) {
                    foreach ($combineFields as $combineField) {
                        if ($typeElement[$combineField["alias"]]) {
                            $arrProperties = unserialize($typeElement[$combineField["alias"]]);
                            foreach($arrProperties as $property) {
                                $properties[$property] = true;
                            }
                        }
                    }
                }
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