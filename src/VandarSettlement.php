<?php

namespace Vandar\VandarCashier;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Vandar\VandarCashier\Controllers\VandarSettlementController;

class VandarSettlement
{

    public static function store($params)
    {
        VandarSettlementController::store($params);
    }

    public static function show($params)
    {
        VandarSettlementController::show($params);
    }

    public static function list($params = null)
    {
        VandarSettlementController::list($params);
    }

    public static function cancel($params)
    {
        VandarSettlementController::cancel($params);
    }

}
