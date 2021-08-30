<?php

namespace Vandar\Cashier\Client;

use Illuminate\Support\Facades\Http;
use Vandar\Cashier\Vandar;

class Client
{

    /**
     * Send Requests to Vandar
     *
     * @param string $method HTTP method ('get', 'post', etc.)
     * @param string $url URL for the request
     * @param boolean $appendHeaders Determines that request has HEADER or no!
     * @param array|null $params list of parameters for the request
     */
    public static function request(string $method, string $url, bool $appendHeaders, array $params = null)
    {
        # Send the request without headers
        if (! $appendHeaders){
            $response = Http::$method($url, $params);
        } else { # Send the request with headers attached
            $access_token = Vandar::Auth()->token();
            $headers = [
                'Authorization' => "Bearer {$access_token}",
                'Accept' => 'application/json'
            ];

            $response = Http::withHeaders($headers)->$method($url, $params);
        }

        if ($response->status() != 200)
        {
            return $response->throw();
        }

        return $response;
    }
}
