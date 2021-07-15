<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use SebastianBergmann\CodeCoverage\Report\PHP;
use Vandar\VandarCashier\Models\VandarSettlement;
use Vandar\VandarCashier\VandarAuth;

class VandarSettlementController extends Controller
{

    /**
     * Store a new settlement
     *
     * @param  $params
     * @return array result
     */
    public static function store($params)
    {
        $response = Http::withToken(VandarAuth::token())->post(self::SETTLEMENT_BASE_URL('/store', 'v3'), [
            'amount' => $params['amount'],
            'iban' => $params['iban'],
            'track_id' => $params['track_id'],
            'payment_number' => $params['payment_number'] ?? NULL,
            'notify_url' => $params['notify_url'] ?? $_ENV['VANDAR_NOTIFY_URL']
        ]);

        $response = json_decode($response);

        if (!$response->status)
            dd($response->error);

        $data = $response->data->settlement[0];

        return $response;
        // dd($data);
    }



    /**
     * Get Complete Details about a settlement
     *
     * @param int $settlement_id
     * @return array settlement details
     */
    public static function getDetails($settlement_id)
    {
        $response = Http::withToken(VandarAuth::token())->get(self::SETTLEMENT_BASE_URL("/$settlement_id"));

        dd(json_decode($response));
    }



    /**
     * Undocumented function
     *
     * @param int $per_page
     * @param int $page
     * @return array List of settlements lsit
     */
    public static function getList($per_page, $page)
    {
        $response = Http::withToken(VandarAuth::token())->get(self::SETTLEMENT_BASE_URL(), [
            'per_page' => $per_page,
            'page' => $page
        ]);

        dd(json_decode($response));
        // return json_decode($response);
    }



    /**
     * Cancel the stored settlement 
     *
     * @param int $transaction_id
     * @return string response message
     */
    public static function cancel($transaction_id)
    {
        $response = Http::withToken(VandarAuth::token())->delete(self::SETTLEMENT_BASE_URL("/$transaction_id"));

        // if (!$response['status']) {
        //     VandarSettlement::where('transaction_id', $transaction_id)
        //         ->update([
        //             'errors' => $response['error']
        //         ]);
        //     dd($response['errors']);
        // }

        // VandarSettlement::where('transaction_id', $transaction_id)
        //     ->update([
        //         'status' => 'CANCELED',
        //     ]);

        $response = json_decode($response);
        // return $response;

        dd($response);
    }



    /**
     * Undocumented function
     *
     * @param string $param(parameters for adding at the end of the request)
     * @param string $version(Vandar API version)
     * @return string SETTLEMENT_BASE_URL
     */
    private static function SETTLEMENT_BASE_URL($param = null, $version = 'v2.1')
    {
        return "https://api.vandar.io/$version/business/{$_ENV['VANDAR_BUSINESS_NAME']}/settlement$param";
    }
}
