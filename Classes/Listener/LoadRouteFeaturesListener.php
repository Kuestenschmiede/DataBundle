<?php


namespace con4gis\DataBundle\Classes\Listener;


use con4gis\MapsBundle\Resources\contao\models\C4gMapProfilesModel;
use con4gis\MapsBundle\Resources\contao\models\C4gMapsModel;
use con4gis\MapsBundle\Resources\contao\models\C4gMapTablesModel;
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
            $inClause = " AND type IN(" . implode(',', $typeSelection) . ")";

            foreach ($points as $point) {

                $bounds = $point->getLatLngBounds($point, $detour);

                $sqlLoc = ' WHERE geox BETWEEN ' . $bounds['left']->getLng() . ' AND ' . $bounds['right']->getLng() . ' AND geoy BETWEEN ' . $bounds['lower']->getLat() . ' AND ' . $bounds['upper']->getLat();

                $strQuery = 'SELECT id, name, geox, geoy FROM tl_c4g_data_element'  . $sqlLoc . $inClause;
                $featurePoint = \Database::getInstance()->prepare($strQuery)->execute()->fetchAllAssoc();
                if (!$this->checkIfArrayContainsFeature($featurePoint[0], $features)) {
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