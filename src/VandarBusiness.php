<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarBusinessController;

class VandarBusiness
{
    public static function list()
    {
        VandarBusinessController::list();
    }

    
    public static function info()
    {
        VandarBusinessController::info();
    }


    public static function users()
    {
        VandarBusinessController::users();
    }
}
