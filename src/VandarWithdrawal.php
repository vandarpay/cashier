<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarWithdrawalController;

class VandarWithdrawal
{

    public static function store($params)
    {
        VandarWithdrawalController::store($params);
    }


    public static function list()
    {
        VandarWithdrawalController::list();
    }


    public static function show($params)
    {
        VandarWithdrawalController::show($params);
    }


    public static function cancel($params)
    {
        VandarWithdrawalController::cancel($params);
    }
}
