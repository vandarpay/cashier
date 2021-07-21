<?php

namespace Vandar\VandarCashier\Utilities;

use Illuminate\Support\Facades\Http;
use Vandar\VandarCashier\VandarAuth;

trait Request
{

    /**
     * Send Requests to Vandar
     *
     * @param string $method HTTP method ('get', 'post', etc.)
     * @param string $url_param URL for the request
     * @param string $params list of parameters for the request
     * @param boolean $header Determines that request has HEADER or no!
     */
    public static function request($method, $url, $header, $params = null)
    {
        # Send Headers without header
        if (!$header)
            return Http::$method($url, $params);



        # Send Headers with headers (Authorization, Accept, ...)
        $access_token = VandarAuth::token();
        $headers = [
            'Authorization' => "Bearer {$access_token}",
            'Accept' => 'application/json'
        ];
        return Http::withHeaders($headers)->$method($url, $params);
    }
}
