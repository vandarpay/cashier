<?php

namespace Vandar\Cashier\Client;

use Vandar\Cashier\Vandar;

class Authenticate
{
    protected const BASE_URL = Vandar::API_BASE_URL . 'v' . Vandar::API_AUTH_VERSION . '/';

    public static function getToken() : string
    {
        // No previous token has been set, issue a completely new token.
        if(is_null(config('vandar.auth.expires_in'))){
            self::setFreshToken();
        }
        // Token exists, but it has expired
        else if(self::hasTokenExpired())
        {
            self::setRefreshedToken();
        }

        return config('vandar.auth.access_token');
    }

    protected static function setToken($array) : void
    {
        $array['expires_in'] += time();
        config()->set('vandar.auth', $array);
    }

    protected static function setFreshToken() : void
    {
        $payload = ['mobile' => config('vandar.mobile'), 'password' => config('vandar.password')];
        $response = Client::request('post', self::BASE_URL . 'login', $payload, false)->json();
        self::setToken($response);
    }

    protected static function setRefreshedToken(): void
    {
        $payload = ['refreshtoken' => config('vandar.auth.refresh_token')];
        $response = Client::request('post', self::BASE_URL . 'refreshtoken', $payload, false)->json();
        self::setToken($response);
    }

    /**
     * Check whether current token has expired
     *
     * @return bool
     */
    protected static function hasTokenExpired(): bool
    {
        return time() >= config('vandar.authentication.expires_in', time() - 10);
    }
}