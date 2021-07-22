<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarWithdrawalController;

class VandarWithdrawal
{

    public static function store($params)
    {
        return VandarWithdrawalController::store($params);
    }


    public static function list()
    {
        return VandarWithdrawalController::list();
    }


    public static function show($params)
    {
        return VandarWithdrawalController::show($params);
    }


    public static function cancel($params)
    {
        return VandarWithdrawalController::cancel($params);
    }
}
