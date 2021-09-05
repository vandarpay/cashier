<?php

namespace Vandar\Cashier\Client;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Vandar\Cashier\Vandar;

// TODO convert class to singleton
class Authenticate
{
    public static function getToken() : string
    {
        // No previous token has been set, issue a completely new token.
        if(is_null(Arr::get(self::getData(), 'access_token'))){
            self::setFreshToken();
        }
        // Token exists, but it has expired
        else if(self::hasTokenExpired())
        {
            self::setRefreshedToken();
        }

        return Arr::get(self::getData(), 'access_token');
    }

    protected static function setToken($array) : void
    {
        $array['expires_in'] += time();
        self::setData($array);
    }

    protected static function setFreshToken() : void
    {
        $payload = ['mobile' => config('vandar.mobile'), 'password' => config('vandar.password')];
        $response = Client::request('post', Vandar::url('AUTH', 'login'), $payload, false)->json();
        self::setToken($response);
    }

    protected static function setRefreshedToken(): void
    {
        $payload = ['refreshtoken' => config('vandar.auth.refresh_token')];
        $response = Client::request('post', Vandar::url('AUTH', 'refreshtoken'), $payload, false)->json();
        self::setToken($response);
    }

    /**
     * Check whether current token has expired
     *
     * @return bool
     */
    protected static function hasTokenExpired(): bool
    {
        return time() >= Arr::get(self::getData(), 'expires_in', time() - 10);
    }

    protected static function getData() : array
    {
        try {
            $data = json_decode(Storage::disk('local')->get('vandar_auth.json'), true);
        } catch (FileNotFoundException $exception)
        {
            $data = [];
        }

        return $data;
    }

    protected static function setData(array $data) : void
    {
        Storage::disk('local')->put('vandar_auth.json', json_encode($data));
    }
}