<?php

namespace Vandar\VandarCashier\Utilities;

use Illuminate\Support\Facades\Http;
use Vandar\VandarCashier\Vandar;
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
    public function request($method, $url, $header, $params = null)
    {
        # Send Headers without header
        if (!$header)
            return $this->verifyResponse(Http::$method($url, $params));



        # Send Headers with headers (Authorization, Accept, ...)
        $access_token = Vandar::Auth()->token();
        $headers = [
            'Authorization' => "Bearer {$access_token}",
            'Accept' => 'application/json'
        ];
        return $this->verifyResponse(Http::withHeaders($headers)->$method($url, $params));
    }


    /**
     * Verify Requests Response by status code
     */
    public function verifyResponse($response)
    {
        if ($response->status() != 200)
            return $response->throw();


        return $response;
    }
}
