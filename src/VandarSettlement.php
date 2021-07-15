<?php

namespace Vandar\VandarCashier;

use Illuminate\Http\Request;
use Vandar\VandarCashier\Controllers\VandarIPGController;
use Illuminate\Support\Facades\Http;
use Vandar\VandarCashier\Controllers\VandarSettlementController;

class VandarSettlement
{

    public static function store($params)
    {
        VandarSettlementController::store($params);
    }

    public static function getDetails($params)
    {
        VandarSettlementController::getDetails($params);
    }

    public static function getList($per_page = 10, $page = 1)
    {
        VandarSettlementController::getList($per_page, $page);
    }

    public static function cancel($params)
    {
        VandarSettlementController::cancel($params);
    }
}
