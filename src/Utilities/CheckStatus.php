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
        $status_name = array_key_last($request_query) == 'payment_status' ? 'payment_status' : 'mandate_status';
        $method_name = Str::camel('check_' . $status_name);
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
                    'errors' => json_encode('Failed Payment'),
                    'status' => $request_query['payment_status']
                ]);

            $request_query['status'] = $request_query['payment_status'];
            unset($request_query['payment_status']);
            return $request_query;
        }

        return (new VandarIPGController)->verifyTransaction($request_query['token']);
    }



    /**
     * check Mandate Status
     * 
     * @param array $request_query
     */
    private static function checkMandateStatus($request_query)
    {
        if ($request_query['status'] != 'SUCCEED') {
            VandarMandate::where('token', $request_query['token'])
                ->update([
                    'errors' => json_encode('Failed To Access'),
                    'status' => $request_query['status']
                ]);

            return $request_query;
        }

        VandarMandate::where('token', $request_query['token'])
            ->update([
                'is_active' => true,
                'status' => $request_query['status'],
                'authorization_id' => $request_query['authorization_id']
            ]);

        return $request_query;
    }
}
