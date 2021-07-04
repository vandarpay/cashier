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
    public  static function pay(array $params)
    {
        VandarIPGController::pay($params);
    }


    /**
     * Get Transaction data by sending TOKEN & API_KEY & save them into Database
     */
    public static function addTransactionData()
    {
        VandarIPGController::addTransactionData();
    }


    /**
     *  Verify the transaction at the end by sending TOKEN & API_KEY
     */
    public static function verifyTransaction()
    {
        VandarIPGController::verifyTransaction();
    }



    /**
     * Check the payment status at the {CallBack Page}
     */
    public static function verifyPayment()
    {
        VandarIPGController::verifyPayment();
    }
}
