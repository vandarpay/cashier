<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarAuthController;


class VandarAuth
{

    /**
     * Get AccessToken 
     * 
     * @param string username(mobile)
     * @param string password
     * 
     * @return 
     */
    public static function token()
    {
        return VandarAuthController::getToken();
    }


    /**
     * Send HTTP Request (login VANDAR account && get initial data)
     * 
     * @param string username(mobile)
     * @param string password
     * 
     * @return json Account access parameters
     */
    public static function login()
    {
        return VandarAuthController::login();
    }


    /**
     * Check is AccessToken expired or no from Database
     * 
     * @return boolean true(expire) / false(not-expire)
     */
    public static function isTokenValid()
    {
        return VandarAuthController::isTokenValid();
    }


    /**
     * Refresh the AccessToken
     * 
     * @return json Account access parameters
     */
    public static function refreshToken()
    {
        return VandarAuthController::refreshToken();
    }
}


