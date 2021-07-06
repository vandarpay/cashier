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
     * @param int payable_type
     * @param int $name
     * @param int $name
     * @param int $name
     */
    public  static function pay($params, $payable_type = null, $payable_id = null, $paymentable_type = null, $paymentable_id = null)
    {
        VandarIPGController::pay($params, $payable_type, $payable_id, $paymentable_type, $paymentable_id);
    }


    /**
     * Check the payment status at the {CallBack Page}
     */
    public static function verifyPayment()
    {
        VandarIPGController::verifyPayment();
    }
}
