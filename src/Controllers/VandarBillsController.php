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
    public static function getBills(int $per_page = null, int $page = null, string $business = null)
    {
        $token = VandarAuth::token();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->get(self::BILLING_URL('transaction'), [
            'per_page' => $per_page ?? 10,
            'page' => $page ?? 1
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
