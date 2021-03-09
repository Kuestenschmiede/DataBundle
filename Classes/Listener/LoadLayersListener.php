<?php
/**
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package   	con4gis
 * @version    7
 * @author  	    con4gis contributors (see "authors.txt")
 * @license 	    LGPL-3.0-or-later
 * @copyright 	Küstenschmiede GmbH Software & Design
 * @link              https://www.con4gis.org
 */
namespace con4gis\DataBundle\Classes\Listener;

use con4gis\DataBundle\Classes\Event\LoadPropertiesEvent;
use con4gis\DataBundle\Classes\Popup\Popup;
use con4gis\DataBundle\Resources\contao\models\DataDirectoryModel;
use con4gis\DataBundle\Resources\contao\models\DataElementModel;
use con4gis\DataBundle\Resources\contao\models\DataTypeModel;
use con4gis\MapsBundle\Classes\Events\LoadLayersEvent;
use con4gis\MapsBundle\Classes\Services\LayerService;
use con4gis\MapsBundle\Resources\contao\models\C4gMapsModel;
use con4gis\ProjectsBundle\Classes\Maps\C4GBrickMapFrontendParent;
use Contao\Controller;
use Contao\Database;
use Contao\StringUtil;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LoadLayersListener
{
    private $layerService;

    private $Database;

    /**
     * LayerContentService constructor.
     * @param LayerService $layerService
     */
    public function __construct(LayerService $layerService)
    {
        $this->layerService = $layerService;
        $this->Database = Database::getInstance();
    }

    public function onLoadLayersLoadDirectories(
        LoadLayersEvent $event,
        $eventName,
        EventDispatcherInterface $eventDispatcher
    ) {
        $dataLayer = $event->getLayerData();
        if (!($dataLayer['type'] === 'mpCntnt_directory')) {
            return;
        }
        $objDataLayer = C4gMapsModel::findByPk($dataLayer['id']);
        if (!$objDataLayer) {
            return;
        }
        $arrDirectoryIds = unserialize($objDataLayer->directorySelection);
        $objDirectories = DataDirectoryModel::findMultipleByIds($arrDirectoryIds);
        $arrDirectories = $objDirectories->fetchAll();
        $typeIds = [];
        foreach ($objDirectories as $directory) {
            $typeIds[$directory->id] = unserialize($directory->types);
        }
        $addData = $event->getAdditionalData();
        $addData['directoryTypeIds'] = $typeIds;
        $addData['directories'] = $arrDirectories;
        $event->setAdditionalData($addData);
    }

    public function onLoadLayersLoadTypes(
        LoadLayersEvent $event,
        $eventName,
        EventDispatcherInterface $eventDispatcher
    ) {
        $dataLayer = $event->getLayerData();
        if (!($dataLayer['type'] === 'mpCntnt') && !($dataLayer['type'] === 'mpCntnt_directory')) {
            return;
        }
        $objData = C4gMapsModel::findByPk($dataLayer['id']);
        $addData = $event->getAdditionalData();
        if ($addData['directoryTypeIds']) {
            $selectedTypes = [];
            foreach ($addData['directoryTypeIds'] as $arrIds) {
                $selectedTypes = array_merge($arrIds, $selectedTypes);
            }
        } else {
            $selectedTypes = unserialize($objData->typeSelection);
            if (!$selectedTypes) {
                return;
            }
        }
        $arrTypes = [];
        foreach ($selectedTypes as $key => $selectedType) {
            $objSelectedTypes = DataTypeModel::findByPk($selectedType);
            if ($objSelectedTypes) {
                $arrTypes[] = $objSelectedTypes->row();
            }
        }
        $addData['types'] = $arrTypes;
        $addData['typeIds'] = $selectedTypes;
        $addData['dataLayer'] = $dataLayer;
        $event->setAdditionalData($addData);
    }

    public function onLoadLayersLoadElements(
        LoadLayersEvent $event,
        $eventName,
        EventDispatcherInterface $eventDispatcher
    ) {
        $dataLayer = $event->getLayerData();
        if (!($dataLayer['type'] === 'mpCntnt') && !($dataLayer['type'] === 'mpCntnt_directory')) {
            return;
        }
        $types = $event->getAdditionalData()['types'];
        $typeIds = $event->getAdditionalData()['typeIds'];
        $arrElements = [];
        foreach ($typeIds as $typeId) {
            $key = array_search($typeId, array_column($types, 'id'));
            $type = $types[$key];
            if ($type['allowPublishing']) {
                $elements = DataElementModel::findRealPublishedBy('type', $typeId);
                if ($elements !== null) {
                    $elements = $elements->fetchAll();
                    $arrElements[$typeId] = $elements;
                }
            } else {
                $elements = DataElementModel::findPublishedBy('type', $typeId);
                if ($elements !== null) {
                    $elements = $elements->fetchAll();
                    $arrElements[$typeId] = $elements;
                }
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
        $dataLayer = $event->getLayerData();
        if (!($dataLayer['type'] === 'mpCntnt') && !($dataLayer['type'] === 'mpCntnt_directory')) {
            return;
        }
        $fmClass = new C4GBrickMapFrontendParent();
        $addData = $event->getAdditionalData();
        $types = $addData['types'];
        $directories = $addData['directories'];
        $directoryTypeIds = $addData['directoryTypeIds'];
        $elements = $addData['elements'];
        $directoryStructures = [];
        if ($directories) {
            // directories on highest level
            foreach ($directories as $directory) {
                $currentTypes = [];
                foreach ($types as $type) {
                    if (in_array($type['id'], $directoryTypeIds[$directory['id']])) {
                        $currentTypes[] = $type;
                    }
                }
                $structureTypes = $this->getStructuresForTypes($currentTypes, $dataLayer, $elements);
                $directoryStructure = $fmClass->createMapStructureElementWithIdCalc(
                    $directory['id'],
                    $dataLayer['id'],
                    $dataLayer['pid'],
                    512,
                    'none',
                    $directory['name'],
                    $directory['name'],
                    true,
                    $dataLayer['hide']
                );
                $directoryStructures[] = $fmClass->createMapStructureChilds($directoryStructure, $structureTypes);
            }

            $dataLayer = $fmClass->createMapStructureChilds($dataLayer, $directoryStructures);
        } else {
            // types on highest level
            $structures = $this->getStructuresForTypes($types, $dataLayer, $elements);
            $dataLayer = $fmClass->createMapStructureChilds($dataLayer, $structures);
        }

        $dataLayer['type'] = 'none';
        $event->setLayerData($dataLayer);
    }

    private function getStructuresForTypes($types, $dataLayer, $elements)
    {
        $structureTypes = [];
        $fmClass = new C4GBrickMapFrontendParent();
        foreach ($types as $type) {
            $structureElems = [];
            $jsonFeatures = [];
            $structureType = $fmClass->createMapStructureElementWithIdCalc(
                $type['id'],
                $dataLayer['id'],
                $dataLayer['pid'],
                419,
                'GeoJSON',
                $type['name'],
                $type['name'],
                true,
                $dataLayer['hide'],
                false,
                false,
                $dataLayer

            );
            $availableFields = unserialize($type['availableFields']);
            if ($availableFields) {
                $strSelect = 'SELECT * FROM tl_c4g_data_custom_field WHERE published = "1" AND type="multicheckbox" AND frontendFilter =1 AND alias IN(';
                foreach ($availableFields as $availableField) {
                    $strSelect .= '"' . $availableField . '",';
                }
                $strSelect = substr($strSelect, 0, strlen($strSelect) - 1) . ')';
                $combineFields = $this->Database->execute($strSelect)->fetchAllAssoc();
            }
            foreach ($elements[$type['id']] as $typeElement) {
                if (intval($typeElement['parentElement']) > 0) {
                    $toMerge = [
                        $typeElement,
                    ];
                    while (intval($toMerge[0]['parentElement']) > 0) {
                        array_unshift($toMerge, DataElementModel::findByPk([$toMerge[0]['parentElement']])->row());
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
                                case 'type':
                                    $merge[$k] = $merge[$k] ?: $v;
                                    if ($merge[$k] === null) {
                                        $merge[$k] = '';
                                    }

                                    break;
                                default:
                                    $merge[$k] = $v ?: $merge[$k];
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
                $popup->generatePopup($typeElement, $availableFields);

                $propertiesEvent = new LoadPropertiesEvent();
                $propertiesEvent->setElementData($typeElement);

                $dispatcher = \Contao\System::getContainer()->get('event_dispatcher');
                $dispatcher->dispatch($propertiesEvent::NAME, $propertiesEvent);

                $properties = $propertiesEvent->getProperties();

                $label = $type['showLabels'] === '1' ? $typeElement['name'] : '';

                $stringClass = $GLOBALS['con4gis']['stringClass'];
                $popupInfo = $stringClass::toHtml5($popup->getPopupString());
                $popupInfo = Controller::replaceInsertTags($popupInfo, false);
                $popupInfo = str_replace(['{{request_token}}', '[{]', '[}]'], [REQUEST_TOKEN, '{{', '}}'], $popupInfo);
                $popupInfo = Controller::replaceDynamicScriptTags($popupInfo);
                $objComments = new \Contao\Comments();
                $popupInfo = $objComments->parseBbCode($popupInfo);
                $properties['popup'] = [
                    'content' => $popupInfo,
                    'routing_link' => $dataLayer['routing_link'],
                    'async' => false,
                ];
                if ($availableFields && $combineFields) {
                    foreach ($combineFields as $combineField) {
                        if ($typeElement[$combineField['alias']]) {
                            $arrProperties = unserialize($typeElement[$combineField['alias']]);
                            foreach ($arrProperties as $property) {
                                $properties[$property] = true;
                            }
                        }
                    }
                }
                $properties['tooltip'] = $typeElement['name'];
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
                    $geoJSON = $this->layerService->createGeoJSONFeature($properties, null, null, $typeElement['geoJson']);
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
                    $content,
                    false,
                    $dataLayer
                );
                $geoJSON['properties']['id'] = $structureElement['id'];
                $jsonFeatures[] = $geoJSON;
                $structureElems[] = $structureElement;
            }

            $structureType = $fmClass->createMapStructureChilds($structureType, $structureElems);
//            $globalJSON = [
//                'type' => 'FeatureCollection',
//                'features' => $jsonFeatures,
//                'properties' => [
//                    'projection' => 'EPSG:4326',
//                ],
//            ];
//            $content = $structureType['childs'][0]['content'][0];
//            $content['data'] = $globalJSON;
//            $content['combinedJSON'] = true;
//            $structureType['content'][0] = $content;
//            $structureType['childs'] = [];
//            $structureType['hasChilds'] = false;
//            $structureType['childsCount'] = 0;

            $structureTypes[] = $structureType;
        }

        return $structureTypes;
    }
}
