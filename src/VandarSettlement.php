<?php

namespace Vandar\VandarCashier;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Vandar\VandarCashier\Controllers\VandarSettlementController;

class VandarSettlement
{

    public static function store($params)
    {
        return VandarSettlementController::store($params);
    }


    public static function show($params)
    {
        return VandarSettlementController::show($params);
    }


    public static function list($params = null)
    {
        return VandarSettlementController::list($params);
    }


    public static function cancel($params)
    {
        return VandarSettlementController::cancel($params);
    }
}
