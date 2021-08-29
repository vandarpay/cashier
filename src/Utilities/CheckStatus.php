<?php

namespace Vandar\Cashier\Utilities;

use Vandar\Cashier\Controllers\VandarIPGController;
use Vandar\Cashier\Models\Mandate;
use Vandar\Cashier\Models\Payment;
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

            Payment::where('token', $request_query['token'])
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
        switch ($request_query['status']) {

            case 'SUCCEED':
                Mandate::where('token', $request_query['token'])
                    ->update([
                        'is_active' => true,
                        'status' => $request_query['status'],
                        'authorization_id' => $request_query['authorization_id']
                    ]);

                break;


            case 'FAILED':
                Mandate::where('token', $request_query['token'])
                    ->update([
                        'errors' => json_encode('Failed_To_Access_Bank_Account'),
                        'status' => $request_query['status']
                    ]);

                break;


            case 'FAILED_TO_ACCESS_BANK':
                Mandate::where('token', $request_query['token'])
                    ->update([
                        'errors' => json_encode('Failed_To_Access_Bank'),
                        'status' => $request_query['status']
                    ]);

                break;


            default:
                break;
        }

        return $request_query;
    }
}
