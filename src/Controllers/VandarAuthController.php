<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use SebastianBergmann\CodeCoverage\Report\PHP;
use Vandar\VandarCashier\Models\VandarAuthList;

class VandarAuthController extends Controller
{
    const LOGIN_BASE_URL = 'https://api.vandar.io/v3';

    public static function getToken()
    {
        if (!(VandarAuthList::count()))
            return (self::login());

        $authData = VandarAuthList::get()->last();

        if (!self::isTokenValid($authData->expires_in))
            return self::refreshToken($authData->refresh_token);

        # return
        return $authData->access_token;
    }


    public static function refreshToken($refresh_token = null)
    {

        ($refresh_token) ?? $refresh_token = (VandarAuthList::get('refresh_token')->last())->refresh_token;

        $response = Http::asForm()->post(self::LOGIN_BASE_URL . '/refreshtoken', [
            'refreshtoken' => $refresh_token,
        ]);

        self::addAuthData($response);

        # return
        return $response;
    }


    public static function login()
    {

        $response = Http::asForm()->post(self::LOGIN_BASE_URL . '/login', [
            'mobile' => $_ENV['VANDAR_USERNAME'],
            'password' => $_ENV['VANDAR_PASSWORD']
        ]);

        self::addAuthData($response);

        # return
        return $response;
        // dd($response);
    }


    public static function isTokenValid($expirationTime = null)
    {
        ($expirationTime) ?? $expirationTime = VandarAuthList::get('expires_in')->last();

        return (time() < $expirationTime);
    }


    private static function addAuthData($response)
    {
        $auth_id = (VandarAuthList::get('id')->last())['id'] ?? 1;

        $response = (array)json_decode($response);

        $response['expires_in'] += time();

        VandarAuthList::updateOrCreate(array('id' => $auth_id), $response);
    }
}
