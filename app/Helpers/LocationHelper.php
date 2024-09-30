<?php

use GuzzleHttp\Client;
use Stevebauman\Location\Facades\Location;

if (!function_exists('get_public_ip')) {
    /**
     * Get the public IP address of the server.
     *
     * @return string|null
     */
    function get_public_ip()
    {
        $client = new Client();
        $response = $client->get('https://api64.ipify.org?format=json');

        $ipData = json_decode($response->getBody(), true);

        return $ipData['ip'] ?? null;
    }
}
