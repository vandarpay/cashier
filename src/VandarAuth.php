<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarAuthController;


class VandarAuth
{

    /**
     * Get the access token for accessing account
     * 
     * @return string
     */
    public static function token()
    {
        return VandarAuthController::getToken();
    }


    /**
     * Login into Vandar account
     * 
     * @return object
     */
    public static function login()
    {
        return VandarAuthController::login();
    }


    /**
     * Check the current token validation
     * 
     * @return boolean 
     */
    public static function isTokenValid()
    {
        return VandarAuthController::isTokenValid();
    }


    /**
     * Refresh Current Token by refresh_token parameter
     * 
     * @return object 
     */
    public static function refreshToken()
    {
        return VandarAuthController::refreshToken();
    }
}
