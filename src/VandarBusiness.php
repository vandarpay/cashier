<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarBusinessController;

class VandarBusiness
{
    public static function list()
    {
        VandarBusinessController::list();
    }

    
    public static function info($business = null)
    {
        VandarBusinessController::info($business);
    }


    public static function users($business = null)
    {
        VandarBusinessController::users($business);
    }
}
