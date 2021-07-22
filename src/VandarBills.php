<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarBillsController;

class VandarBills
{

    public static function list($params = null)
    {
        return VandarBillsController::list($params);
    }

    public static function balance()
    {
        return VandarBillsController::balance();
    }
}
