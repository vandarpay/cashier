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
        VandarWithdrawalController::store();
    }


    public static function show()
    {
        VandarWithdrawalController::store();
    }


    public static function cancel()
    {
        VandarWithdrawalController::store();
    }
}
