<?php

namespace Vandar\VandarCashier;

use Illuminate\Http\Request;
use Vandar\VandarCashier\Controllers\VandarIPGController;
use Illuminate\Support\Facades\Http;

class VandarIPG
{

    /**
     * Send payment parameters to get Payment Token
     * 
     * @param associative_array array of payment parameters
     */
    public  static function pay(array $params, $payableId = null, $payableType = null, $paymentableId = null, $paymentableType = null)
    {
        VandarIPGController::pay($params);
    }


    /**
     * Check the payment status at the {CallBack Page}
     */
    public static function verifyPayment()
    {
        VandarIPGController::verifyPayment();
    }
}
