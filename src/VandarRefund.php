<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarRefundController;

class VandarRefund
{

    /**
     * Refund successfull payment to card number
     *
     * @param array $params
     * 
     * @return array $response->data
     */
    public static function refund($params)
    {
        VandarRefundController::refund($params);
    }
}
