<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarBusinessController;

class VandarBusiness
{
    public static function list()
    {
        return VandarBusinessController::list();
    }


    public static function info()
    {
        return VandarBusinessController::info();
    }


    public static function users($params = null)
    {
        return VandarBusinessController::users($params);
    }
}
