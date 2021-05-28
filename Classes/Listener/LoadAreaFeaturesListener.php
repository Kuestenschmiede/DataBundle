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

use con4gis\DataBundle\Classes\Popup\Popup;
use con4gis\DataBundle\Resources\contao\models\DataTypeModel;
use con4gis\MapsBundle\Resources\contao\models\C4gMapProfilesModel;
use con4gis\MapsBundle\Resources\contao\models\C4gMapsModel;
use con4gis\MapsBundle\Classes\Events\LoadAreaFeaturesEvent;
use con4gis\MapsBundle\Classes\LatLng;
use con4gis\MapsBundle\Classes\Services\AreaService;
use con4gis\MapsBundle\Entity\RoutingConfiguration;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LoadAreaFeaturesListener
{
    public function onLoadAreaFeaturesGetFeatures(
        LoadAreaFeaturesEvent $event,
        $eventName,
        EventDispatcherInterface $eventDispatcher
    ) {
        $profileId = $event->getProfileId();
        $location = $event->getLocation();
        $distance = $event->getDistance();
        $layerId = $event->getLayerId();
        $profile = $event->getProfile();
        $objMapsProfile = C4gMapProfilesModel::findBy('id', $profileId);
        $coords = explode(',', $location);
        $point = new LatLng($coords[0], $coords[1]);
        $bounds = $point->getLatLngBounds($point, $distance);

        $objLayer = C4gMapsModel::findById($layerId);
        $routerConfigRepo = \Contao\System::getContainer()->get('doctrine.orm.default_entity_manager')
            ->getRepository(RoutingConfiguration::class);
        $routerConfig = $routerConfigRepo->findOneBy(['id' => $objMapsProfile->routerConfig]);
        if ($routerConfig instanceof RoutingConfiguration) {
            $type = $routerConfig->getRouterApiSelection();
            if ($objLayer->location_type == 'mpCntnt') {
                $typeSelection = unserialize($objLayer->typeSelection);
                $inClause = ' AND type IN(' . implode(',', $typeSelection) . ')';
                $sqlLoc = ' WHERE geox BETWEEN ' . $bounds['left']->getLng() . ' AND ' . $bounds['right']->getLng() . ' AND geoy BETWEEN ' . $bounds['lower']->getLat() . ' AND ' . $bounds['upper']->getLat();
                $sqlWhere = " AND (publishFrom >= ? OR publishFrom = '') AND (publishTo < ? OR publishTo = '') AND published='1'";
                $sqlSelect = '*, name AS label, name AS tooltip';//" id, type, geox, geoy, name AS label, name AS tooltip";
                $strQuery = 'SELECT' . $sqlSelect . ' FROM tl_c4g_data_element' . $sqlLoc . $inClause . $sqlWhere;
                $pointFeatures = \Contao\Database::getInstance()->prepare($strQuery)->execute(time(), time())->fetchAllAssoc();
                $responseFeatures = [];
                $locations = [];
                $locations[] = [$point->getLng(), $point->getLat()];
                foreach ($pointFeatures as $pointFeature) {
                    $pTemp = new LatLng($pointFeature['geoy'], $pointFeature['geox']);
                    if ($pTemp->getDistance($point) < $distance) {
                        $objSelectedType = DataTypeModel::findByPk($pointFeature['type']);
                        $availableFields = unserialize($objSelectedType->availableFields);
                        $popup = new Popup();
                        $popup->generatePopup($pointFeature, $availableFields);
                        $pointFeature['popup'] = $popup->getPopupString();
                        $responseFeatures[] = $pointFeature;
                        $locations[] = [$pTemp->getLng(), $pTemp->getLat()];
                    }
                }
                $areaService = new AreaService();
                $performMatrix = $areaService->performMatrix($objMapsProfile, $profile, $locations);
                if ($performMatrix) {
                    $requestData = \GuzzleHttp\json_decode($performMatrix, true);
                    $type = $requestData['responseType'] ?: $type;
                } else {
                    $requestData = [];
                }

                $event->setReturnData([$requestData, $responseFeatures, $type, 'notOverpass']);
            }
        }
    }
}
