<?php

namespace Vandar\Cashier\Client;

use Illuminate\Http\Client\Response;
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
     * @param array|null $payload list of parameters for the request
     */
    public static function request(string $method, string $url, array $payload = null, bool $appendHeaders=true) : Response
    {
        # Send the request without headers
        if (! $appendHeaders){
            /** @var Response $response */
            $response = Http::accept('application/json')->$method($url, $payload);
        } else { # Send the request with headers attached
            /** @var Response $response */
            $response = Http::accept('application/json')
                ->withToken(Authenticate::getToken())
                ->$method($url, $payload);
        }

        if ($response->status() != 200)
        {
            dd($response->body());
        }

        return $response;
    }
}
