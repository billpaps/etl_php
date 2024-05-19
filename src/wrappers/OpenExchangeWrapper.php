<?php

namespace wrappers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

use schemas\Exchange;

class OpenExchangeWrapper {

    public function get_historical_data(string $app_id, string $date) : array {
        $endpoint = "https://openexchangerates.org/api/historical/$date.json?app_id=$app_id";
        $client = new Client();
        try {
            $response = $client->request('GET', $endpoint);
        } catch (GuzzleException $e) {
            exit($e->getMessage());
        }

        // Get the response body as a string
        $responseBody = $response->getBody()->getContents();

        return json_decode($responseBody, true);

    }
}