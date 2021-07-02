<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use SebastianBergmann\CodeCoverage\Report\PHP;
use Vandar\VandarCashier\Models\VandarAuthList;

class VandarAuthController extends Controller
{

    public static function getToken()
    {
        if (!(VandarAuthList::count()))
            return (self::login());

        $authData = VandarAuthList::get()->last();

        if (!self::isTokenValid($authData->expires_in))
            return self::refreshToken($authData->refresh_token);

        return $authData->access_token;
    }


    public static function refreshToken($refreshToken = null)
    {
        ($refreshToken) ?? $refreshToken = (VandarAuthList::get('refresh_token')->last())->refresh_token;

        $response = Http::asForm()->post('https://api.vandar.io/v3/refreshtoken', [
            'refreshtoken' => $refreshToken
        ]);



        self::addAuthData($response);

        return $response;
    }


    public static function login()
    {
        $response = Http::asForm()->post('https://api.vandar.io/v3/login', [
            'mobile' => $_ENV['VANDAR_USERNAME'],
            'password' => $_ENV['VANDAR_PASSWORD'],
        ]);

        self::addAuthData($response);

        return $response;
    }


    public static function isTokenValid($expirationTime = null)
    {
        ($expirationTime) ?? $expirationTime = VandarAuthList::get('expires_in')->last();

        return (time() < $expirationTime);
    }


    private static function addAuthData($response)
    {
        $auth_id = (VandarAuthList::get('id')->last())['id'] ?? 1;

        VandarAuthList::updateOrCreate(
            array('id' => $auth_id),
            [
                'token_type' =>  $response['token_type'],
                'expires_in' =>  $response['expires_in'] + time(),
                'access_token' =>  $response['access_token'],
                'refresh_token' =>  $response['refresh_token'],
            ]
        );
    }
}
