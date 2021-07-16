<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Vandar\VandarCashier\VandarAuth;

class VandarWalletController extends Controller
{
    public static $WALLET_BASE_URL;

    /**
     * Get Wallet Balance
     *
     * @return array $data
     */
    public static function balance($business = null)
    {
        self::setUrl($business);

        $response = Http::withToken(VandarAuth::token())->get(self::$WALLET_BASE_URL);

        // $data = json_decode($response)->data;

        dd($response);
        // return $data;
    }


    /**
     * Set Wallet Base Url
     *
     * @return void
     */
    private static function setUrl($business = null)
    {
        $business = $business ?? $_ENV['VANDAR_BUSINESS_NAME'];

        self::$WALLET_BASE_URL  = "https://api.vandar.ir/v2/business/$business/balance";
    }
}
