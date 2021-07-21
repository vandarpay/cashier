<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VandarBusinessController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;

    const BUSINESS_BASE_URL = 'https://api.vandar.io/v2/business/';

    /**
     * Get the list of businesses
     *
     * @return array
     */
    public static function list()
    {
        $response = self::request('get', self::BUSINESS_URL(), true);

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
    public static function info()
    {
        $response = self::request('get', self::BUSINESS_URL($_ENV['VANDAR_BUSINESS_NAME']), true);

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
    public static function users()
    {
        $response = self::request('get', self::BUSINESS_URL($_ENV['VANDAR_BUSINESS_NAME'], '/iam'), true);

        if ($response->status() != 200)
            dd($response->object()->error);



        # return $response->object()->data;
        dd($response->object()->data);
    }


    /**
     * Business URL
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
