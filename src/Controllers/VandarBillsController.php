<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Vandar\VandarCashier\VandarAuth;

class VandarBillsController extends Controller
{

    /**
     * Get Wallet Balance
     *
     * @return array $data
     */
    public static function balance(string $business = null)
    {
        $token = VandarAuth::token();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->get(self::BILLING_URL('balance'));

        $data = json_decode($response)->data;

        # return
        dd($data);
        // return $data;
    }




    /**
     * Get Bills List
     *
     * @return array $data
     */
    public static function getBills($params)
    {
        $token = VandarAuth::token();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->get(self::BILLING_URL('transaction'), [
            $params['per_page'] => $per_page ?? 10,
            $params['page'] => $page ?? 1,
            $params['fromDate'] => $params['fromDate'] ?? NULL,
            $params['toDate'] => $params['toDate'] ?? NULL,
            $params['statusKind'] => $params['statusKind'] ?? NULL, # transactions|settlements
            $params['status'] => $params['status'] ?? NULL,  # succeed|failed|pending|canceled 
            $params['channel'] => $params['channel'] ?? NULL, # ipg|form|p2p|subscription|settlements
            $params['formId'] => $params['formId'] ?? NULL,
            $params['ref_id'] => $params['ref_id'] ?? NULL,
            $params['tracking_code'] => $params['tracking_code'] ?? NULL,
            $params['q'] => $params['q'] ?? NULL

        ]);

        $data = json_decode($response)->data;

        # return
        dd($data);
        // return $data;
    }



    /**
     * Set Bills Base Url
     *
     * @param string $param
     * @param string|null $business
     * 
     * @return string $url 
     */
    private static function BILLING_URL(string $param, string $business = null)
    {
        $business = $business ?? $_ENV['VANDAR_BUSINESS_NAME'];

        $url = "https://api.vandar.io/v2/business/$business/$param";

        return $url;
    }
}
