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
     * @param boolean $header Determines that request has HEADER or no!
     * @param array|null $params list of parameters for the request
     */
    public function request(string $method, string $url, bool $header, array $params = null)
    {
        if (is_array($params) and array_key_exists('business', $params))
            unset($params['business']);


        # Send Headers without header
        if (!$header)
            return $this->checkResponse(Http::$method($url, $params));



        # Send Headers with headers (Authorization, Accept, ...)
        $access_token = Vandar::Auth()->token();
        $headers = [
            'Authorization' => "Bearer {$access_token}",
            'Accept' => 'application/json'
        ];
        return $this->checkResponse(Http::withHeaders($headers)->$method($url, $params));
    }


    /**
     * Verify Requests Response by status code
     */
    public function checkResponse($response)
    {
        if ($response->status() != 200)
            return $response->throw();


        return $response;
    }
}
