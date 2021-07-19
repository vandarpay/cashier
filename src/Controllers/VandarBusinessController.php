<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Vandar\VandarCashier\VandarAuth;

class VandarBusinessController extends Controller

{
    const BUSINESS_BASE_URL = 'https://api.vandar.io/v2/business/';

    /**
     * Get the list of businesses
     *
     * @return array
     */
    public static function list()
    {
        $response = self::request();

        if ($response->status() != 200)
            dd($response->object()->error);


        # return $response->data;
        dd($response->object()->data);
    }



    /**
     * Get Business Information
     *
     * @param string|null $business
     * 
     * @return object $business_info
     */
    public static function info($business = null)
    {
        $business = $business ?? $_ENV['VANDAR_BUSINESS_NAME'];

        $response = self::request($business);


        if ($response->status() != 200)
            dd($response->object()->error);


        # return $response->object()->data;
        dd($response->object()->data);
    }



    /**
     * Get Business Users
     *
     * @param string|null $business
     * 
     * @return object 
     */
    public static function users($business = null)
    {
        $business = $business ?? $_ENV['VANDAR_BUSINESS_NAME'];

        $response = self::request($business, '/iam');


        if ($response->status() != 200)
            dd($response->object()->error);



        # return $response->object()->data;
        dd($response->object()->data);
    }



    /**
     * Send Request for Business
     *
     * @param string|null $business
     * @param string|null $url_param
     */
    public static function request($business = null, $url_param = null)
    {
        $token = VandarAuth::token();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->get(self::BUSINESS_URL($business, $url_param));

        return $response;
    }



    /**
     * Prepare Business Url for sending request
     *
     * @param string|null $business
     * @param string|null $param
     * 
     * @return string
     */
    private static function BUSINESS_URL(string $business = null, string $param = null)
    {
        return self::BUSINESS_BASE_URL . $business . $param;
    }
}
