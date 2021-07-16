<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Vandar\VandarCashier\VandarAuth;

class VandarBusinessController extends Controller

{

    /**
     * Get the list of businesses
     *
     * @return array $response
     */
    public static function getList()
    {
        # Request
        $response = self::request();

        $response = json_decode($response);



        if (!$response->status) {
            # return
            // return $response->error;
            dd($response->error);
        }


        # return $response->data;
        dd($response->data);
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

        # Request
        $response = self::request($business);

        $response = json_decode($response);

        if (!$response->status) {
            # return $response->error;
            dd($response->error);
        }


        # return $response->data;
        dd($response->data);
    }



    /**
     * Get Business Users
     *
     * @param string|null $business
     * 
     * @return object $business_users
     */
    public static function users($business = null)
    {
        $business = $business ?? $_ENV['VANDAR_BUSINESS_NAME'];

        # Request
        $response = self::request($business, '/iam');

        $response = json_decode($response);

        if (!$response->status) {
            # return $response->error;
            dd($response->error);
        }


        # return $response->data;
        dd($response->data);
    }



    ######################## REQUEST ########################
    /**
     * Send Request By Guzzle
     *
     * @param string|null $business
     * @param string|null $url_param
     * 
     * @return string $response
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
     * @return string $url
     */
    public static function BUSINESS_URL(string $business = null, string $param = null)
    {
        $url = "https://api.vandar.io/v2/business/{$business}{$param}";
        return $url;
    }
}
