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
use con4gis\RoutingBundle\Classes\Event\LoadRouteFeaturesEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LoadRouteFeaturesListener
{
    public function onLoadRouteFeaturesGetFeatures(
        LoadRouteFeaturesEvent $event,
        $eventName,
        EventDispatcherInterface $eventDispatcher
    ) {
        $features = [];
        $profileId = $event->getProfileId();
        $points = $event->getPoints();
        $layerId = $event->getLayerId();
        $detour = $event->getDetour();
        $objMapsProfile = C4gMapProfilesModel::findBy('id', $profileId);
        $objLayer = C4gMapsModel::findById($layerId);
        if ($objLayer->location_type == 'mpCntnt') {
            $typeSelection = unserialize($objLayer->typeSelection);
            $inClause = ' AND type IN(' . implode(',', $typeSelection) . ')';
            $sqlSelect = ' *, name AS label, name AS tooltip';
            $sqlWhere = " AND (publishFrom >= ? OR publishFrom = '') AND (publishTo < ? OR publishTo = '') AND published='1'";

            foreach ($points as $point) {
                $bounds = $point->getLatLngBounds($point, $detour);

                $sqlLoc = ' WHERE geox BETWEEN ' . $bounds['left']->getLng() . ' AND ' . $bounds['right']->getLng() . ' AND geoy BETWEEN ' . $bounds['lower']->getLat() . ' AND ' . $bounds['upper']->getLat();

                $strQuery = 'SELECT ' . $sqlSelect . ' FROM tl_c4g_data_element' . $sqlLoc . $inClause . $sqlWhere;
                $featurePoint = \Contao\Database::getInstance()->prepare($strQuery)->execute(time(), time())->fetchAllAssoc();
                if (!$this->checkIfArrayContainsFeature($featurePoint[0], $features)) {
                    foreach ($featurePoint as $key => $singleFeature) {
                        $objSelectedType = DataTypeModel::findByPk($singleFeature['type']);
                        $availableFields = unserialize($objSelectedType->availableFields);
                        $popup = new Popup();
                        $popup->generatePopup($singleFeature, $availableFields);
                        $singleFeature['popup'] = $popup->getPopupString();
                        $featurePoint[$key] = $singleFeature;
                    }
                    $features = array_merge($features, $featurePoint);
                }
            }
            $event->setFeatures($features);
        }
    }
    private function checkIfArrayContainsFeature($feature, $array)
    {
        foreach ($array as $entry) {
            if ($entry['id'] === $feature['id']) {
                return true;
            }
        }

        return false;
    }
}
