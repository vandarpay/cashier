<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Vandar\VandarCashier\Models\VandarMandate;

class VandarMandateController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;

    const MANDATE_REDIRECT_URL = 'https://subscription.vandar.io/authorizations/';


    /**
     * Get the list of confirmed Mandates
     *
     * @return object
     */
    public static function list()
    {
        $response = self::request('get', self::MANDATE_URL(), true);

        return $response->json();
    }



    /**
     * Store new Mandate
     *
     * @param array $params
     */
    public static function store($params)
    {
        $params['expiration_date'] = $params['expiration_date'] ?? date('Y-m-d', strtotime(date('Y-m-d') . ' + 3 years'));
        $params['callback_url'] = $params['callback_url'] ?? $_ENV['VANDAR_CALLBACK_URL'];

        $response = self::request('post', self::MANDATE_URL('store'), true, $params);

        $params['token'] = $response->json()['result']['authorization']['token'];


        VandarMandate::create($params);


        return Redirect::away(self::MANDATE_REDIRECT_URL . $params['token']);
    }



    /**
     * Show the mandate details
     *
     * @param string $subscription_code
     * 
     * @return array
     */
    public static function show($authorization_id)
    {
        $response = self::request('get', self::MANDATE_URL($authorization_id), true);

        return $response->json();
    }


    /**
     * Revoke Confirmed mandates
     *
     * @param string $subscription_code
     * 
     * @return array
     */
    public static function revoke($authorization_id)
    {
        $response = self::request('delete', self::MANDATE_URL($authorization_id), true);

        VandarMandate::where('authorization_id', $authorization_id)
            ->update(['is_active' => false]);

        return $response->json();
    }


    /**
     * Prepare Mandate Url for sending requests
     *
     * @param string|null $param
     * 
     * @return string $url
     */
    private static function MANDATE_URL(string $param = null)
    {
        return "https://api.vandar.io/v2/business/$_ENV[VANDAR_BUSINESS_NAME]/subscription/authorization/$param";
    }
}
