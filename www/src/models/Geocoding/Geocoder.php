<?php

namespace Geocoding;

use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;

class Geocoder {

    private $API_KEY = 'baf56999cf8a7d';
    private $URL = [ //https://api.locationiq.com/v1/autocomplete.php
        'autocomplete' => 'https://api.locationiq.com/v1/autocomplete.php'
    ]; // https://api.locationiq.com/v1/autocomplete.php?key=baf56999cf8a7d&q=N%C4%99dza

    function getAPIkey() {
        return $this->API_KEY;
    }

    function __construct($search_string) {
        global $app;

        if (!$search_string) {
            return $app->json(['locations' => false]);
        }

        $httpClient = new \Http\Adapter\Guzzle6\Client();
        $provider = new \Geocoder\Provider\GoogleMaps\GoogleMaps($httpClient, null, $this->getAPIkey());
        $geocoder = new \Geocoder\StatefulGeocoder($provider, 'pl');

        $result = $geocoder->geocodeQuery(GeocodeQuery::create('Racibórz, ul. Rudzka'));

        var_dump($result);
        die();
    }

}