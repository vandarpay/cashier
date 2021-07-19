<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Vandar\VandarCashier\VandarAuth;

class VandarBillsController extends Controller
{
    const BASE_BILLING_URL = 'https://api.vandar.io/v2/business/';

    /**
     * Get Wallet Balance
     *
     * @return array $data
     */
    public static function balance()
    {
        $response = self::request('balance');

        if ($response->status() != 200)
            dd($response->object()->error);

        # return $response->object();
        dd($response->object()->data);
    }




    /**
     * Get Bills List
     *
     * @return array $data
     */
    public static function list($params)
    {
        $response = self::request('transaction', $params);

        if ($response->status() != 200)
            dd($response->object()->error);

        # return $response->object()->data;
        dd($response->object()->data);
    }



    /**
     * Send Request for Billing
     *
     * @param string $url_param
     * @param array $params 
     */
    private static function request($url_param, $params = null)
    {
        $access_token = VandarAuth::token();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
        ])->get(self::BILLING_URL($url_param), $params);

        return $response;
    }



    /**
     * Set Billing Url
     *
     * @param string $param
     * 
     * @return string  
     */
    private static function BILLING_URL(string $param)
    {
        return self::BASE_BILLING_URL . $_ENV['VANDAR_BUSINESS_NAME'] . "/$param";
    }
}
