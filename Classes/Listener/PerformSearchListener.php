<?php

namespace con4gis\DataBundle\Classes\Listener;

use con4gis\MapsBundle\Classes\Events\PerformSearchEvent;
use con4gis\MapsBundle\Resources\contao\models\C4gMapProfilesModel;
use con4gis\MapsBundle\Resources\contao\modules\api\SearchApi;
use Contao\Database;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;

class PerformSearchListener
{
    private $Database;

    /**
     * LayerContentService constructor.
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->Database = Database::getInstance();
    }
    public function onPerformSearchDoIt(
        PerformSearchEvent $event,
        $eventName,
        EventDispatcher $eventDispatcher
    ) {
        $profileId = $event->getProfileId();
        $arrParams = $event->getArrParams();
        $response = $event->getResponse();
        $profile = C4gMapProfilesModel::findByPk($profileId);
        if ($profile && $profile->ownGeosearch) {
            $arrColumns = unserialize($profile->searchFields);

            $arrDBResult = SearchApi::searchDatabase($arrParams['q'], $arrColumns, 'tl_c4g_data_element', $this->Database);
            $arrResults = [];
            foreach ($arrDBResult as $dBResult) {
                $address = $dBResult['addressName'] ?: $dBResult['name'];
                if ($dBResult['addressStreet'] && $dBResult['addressStreetNumber']) {
                    $address .= ', ';
                    $address .= $dBResult['addressStreet'] ?: $dBResult['locationStreet'];
                    $address .= ' ';
                    $address .= $dBResult['addressStreetNumber'];
                }
                if ($dBResult['addressZip'] && $dBResult['addressCity']) {
                    $address .= ', ';
                    $address .= $dBResult['addressZip'];
                    $address .= ' ';
                    $address .= $dBResult['addressCity'];
                }
                $arrResults[] = [
                    'lat' => $dBResult['geoy'],
                    'lon' => $dBResult['geox'],
                    'display_name' => $address,
                ];
                $arrResults = array_merge($arrResults, $response);
                $arrResults = array_slice($arrResults, 0, $arrParams['limit'] ?: 10);
                $event->setResponse($arrResults);
            }
        }
    }
}
