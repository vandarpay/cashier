<?php

namespace Vandar\Cashier\Client;


use GuzzleHttp;
use Psr\Http\Message\ResponseInterface;

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
    public static function request(string $method, string $url, array $payload = null, bool $appendHeaders = true): ResponseInterface
    {
        $client = new GuzzleHttp\Client();
        $stack = new GuzzleHttp\HandlerStack(new GuzzleHttp\Handler\CurlHandler());
        $stack->push(GuzzleHttp\Middleware::mapResponse(function (ResponseInterface $response) {
            return new CustomResponse($response);
        }));

        $options = [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'handler' => $stack,
            'http_errors' => false,
        ];

        if ($payload) {
            $options['form_params'] = $payload;
        }

        # Send the request without headers
        if ($appendHeaders) {
            $options['headers']['Authorization'] = 'Bearer ' . Authenticate::getToken();
        }

        $response = $client->request($method, $url, $options);
        Thrower::process($response, ['url' => $url, 'options' => $options]);

        return $response;
    }
}
