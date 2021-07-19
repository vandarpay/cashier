<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarBillsController;

class VandarBills
{

    public static function list($params = null)
    {
        VandarBillsController::list($params);
    }

    public static function balance()
    {
        VandarBillsController::balance();
    }
}
