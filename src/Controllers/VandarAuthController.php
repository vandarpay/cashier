<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        ($refreshToken) ?? $refreshToken = VandarAuthList::get('refresh_token')->last();

        #2 Send request to  API address with refresh_token
        $jsonResponse = "{name : amir}";

        // $jsonResponse = file_get_contents(dirname(__DIR__, 4) . '/packageData.json');

        $objectResponse = json_decode($jsonResponse);

        self::addAuthData($objectResponse);

        return $jsonResponse;
    }


    public static function login()
    {
        # Get data from HTTP Request with $_ENV['VANDAR_USERNAME']    $_ENV['VANDAR_PASSWORD']
        $jsonResponse = "{name : amir}";

        // $jsonResponse = file_get_contents(dirname(__DIR__, 4) . '/packageData.json');

        $objectResponse = json_decode($jsonResponse);

        self::addAuthData($objectResponse);

        return $jsonResponse;
    }


    public static function isTokenValid($expirationTime = null)
    {
        ($expirationTime) ?? $expirationTime = VandarAuthList::get('expires_in')->last();

        return (time() < $expirationTime);
    }


    private static function addAuthData($objectResponse)
    {
        $auth_id = (VandarAuthList::get('id')->last())['id'] ?? 1;

        VandarAuthList::updateOrCreate(
            array('id' => $auth_id),
            [
                'token_type' =>  $objectResponse->token_type,
                'expires_in' =>  $objectResponse->expires_in + time(),
                'access_token' =>  $objectResponse->access_token,
                'refresh_token' =>  $objectResponse->refresh_token,
            ]
        );
    }
}
