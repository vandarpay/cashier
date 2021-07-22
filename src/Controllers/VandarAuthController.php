<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use SebastianBergmann\CodeCoverage\Report\PHP;
use Vandar\VandarCashier\Models\VandarAuthList;

class VandarAuthController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;

    const LOGIN_BASE_URL = 'https://api.vandar.io/v3/';


    /**
     * Get the access token for accessing account
     *
     * @return string
     */
    public static function getToken()
    {
        if (!(VandarAuthList::count()))
            return (self::login()['access_token']);

        $authData = VandarAuthList::get()->last();

        if (!self::isTokenValid($authData['expires_in']))
            return self::refreshToken($authData['refresh_token'])['access_token'];

        return $authData['access_token'];
    }


    /**
     * Login into Vandar account
     *
     * @return object 
     */
    public static function login()
    {
        $params = ['mobile' => $_ENV['VANDAR_USERNAME'], 'password' => $_ENV['VANDAR_PASSWORD']];

        $response = self::request('post', self::LOGIN_URL('login'), false, $params);

        self::addAuthData($response->json());

        return $response->json();
    }




    /**
     * Refresh Current Token by refresh_token parameter
     *
     * @param string $refresh_token
     * 
     * @return object
     */
    public static function refreshToken($refresh_token = null)
    {

        $refresh_token = $refresh_token ?? (VandarAuthList::get('refresh_token')->last())->refresh_token;

        $params = ['refreshtoken' => $refresh_token];
        $response = self::request('post', self::LOGIN_URL('refreshtoken'), false, $params);

        self::addAuthData($response->json());

        return $response->json();
    }




    /**
     * Check the current token validation
     *
     * @param int $expirationTime
     * 
     * @return boolean
     */
    public static function isTokenValid($expirationTime = null)
    {
        ($expirationTime) ?? $expirationTime = VandarAuthList::get('expires_in')->last();

        return (time() < $expirationTime);
    }



    /**
     * Add new authentication data into database
     *
     * @param array $response
     */
    private static function addAuthData($response)
    {
        $auth_id = (VandarAuthList::get('id')->last())['id'] ?? 1;

        // $response = (array)$response;

        $response['expires_in'] += time();

        VandarAuthList::updateOrCreate(['id' => $auth_id], $response);
    }




    /**
     * Login URL
     *
     * @param string|null $param
     * 
     * @return string 
     */
    private static function LOGIN_URL(string $param = null)
    {
        return self::LOGIN_BASE_URL . $param;
    }
}
