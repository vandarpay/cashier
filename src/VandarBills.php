<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarBillsController;

class VandarBills
{

    public static function getBills($per_page = null, $page = null, $business = null)
    {
        VandarBillsController::getBills($per_page, $page, $business = null);
    }

    public static function balance()
    {
        VandarBillsController::balance($business = null);
    }
}
