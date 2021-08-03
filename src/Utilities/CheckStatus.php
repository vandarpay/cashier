<?php

namespace Vandar\VandarCashier\Utilities;

use Vandar\VandarCashier\Controllers\VandarIPGController;
use Vandar\VandarCashier\Models\VandarMandate;
use Vandar\VandarCashier\Models\VandarPayment;
use Illuminate\Support\Str;

trait CheckStatus
{
    /**
     * Verify Methods Handler
     *
     * @param array $request_query
     */
    public static function checkerIndex($request_query)
    {
        $method_name = Str::camel('check_' . array_key_last($request_query));
        return self::$method_name($request_query);
    }



    /**
     * check Payment Status
     * 
     * @param array $request_query
     */
    private static function checkPaymentStatus($request_query)
    {
        if ($request_query['payment_status'] != 'OK') {

            VandarPayment::where('token', $request_query['token'])
                ->update([
                    'errors' => json_encode('failed payment'),
                    'status' => 'FAILED'
                ]);

            return $request_query;
        }

        return (new VandarIPGController)->verifyTransaction($request_query['token']);
    }



    /**
     * check Mandate Status
     * 
     * @param array $request_query
     */
    private static function checkStatus($request_query)
    {
        if ($request_query['status'] != 'SUCCEED') {
            VandarMandate::where('token', $request_query['token'])
                ->update([
                    'errors' => json_encode('Failed To Access'),
                    'status' => 'FAILED'
                ]);

            return $request_query;
        }

        VandarMandate::where('token', $request_query['token'])
            ->update([
                'status' => 'SUCCEED',
                'is_active' => true,
                'authorization_id' => $request_query['authorization_id']
            ]);

        return $request_query;
    }
}
