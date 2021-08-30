<?php

namespace Vandar\Cashier\Client;


use Psr\Http\Message\ResponseInterface;
use Vandar\Cashier\Vandar;
use GuzzleHttp;

class Client
{

    /**
     * Send Requests to Vandar
     *
     * @param string $method HTTP method ('get', 'post', etc.)
     * @param string $url URL for the request
     * @param boolean $appendHeaders Determines that request has HEADER or no!
     * @param array|null $payload list of parameters for the request
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public static function request(string $method, string $url, array $payload = null, bool $appendHeaders=true) : ResponseInterface
    {
        $client = new GuzzleHttp\Client();
        $options = [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ];

        if ($payload){
            $options['form_params'] = $payload;
        }

        # Send the request without headers
        if ($appendHeaders){
            $options['headers']['Authorization'] = 'Bearer ' . Authenticate::getToken();

        }

        return $client->request($method, $url, $options);
    }
}
