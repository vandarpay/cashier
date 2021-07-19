<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vandar\VandarCashier\VandarAuth;
use Illuminate\Support\Facades\Http;
use Vandar\VandarCashier\Models\VandarMandate;

class VandarMandateController extends Controller
{
    const MANDATE_REDIRECT_URL = 'https://subscription.vandar.io/authorizations/';


    /**
     * Get the list of confirmed Mandates
     *
     * @return object
     */
    public static function list()
    {
        $response = self::request('get');

        if ($response->status() != 200)
            dd($response->object()->errors ?? $response->object()->error);

        # return $response->object();
        dd($response->object());
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

        $response = self::request('post', $params, 'store');


        if ($response->status() != 200)
            dd($response->object()->errors ?? $response->object()->error);


        $params['token'] = $response->object()->result->authorization->token;


        VandarMandate::create($params);


        dd(self::MANDATE_REDIRECT_URL . $params['token']);
        // return redirect()->away(self::MANDATE_REDIRECT_URL . $token);
        // return redirect(self::MANDATE_REDIRECT_URL . $token);
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
        $response = self::request('get', '', $authorization_id);

        if ($response->status() != 200)
            dd($response->object()->errors ?? $response->object()->error);


        # return $response->object();
        dd($response->object());
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
        $response = self::request('delete', '', $authorization_id);


        if ($response->status() != 200)
            dd($response->object()->errors ?? $response->object()->error ?? $response->object()->message);


        VandarMandate::where('authorization_id', $authorization_id)
            ->update(['is_active' => false]);

        # return $response->object();
        dd($response->object());
    }



    /**
     * Check the Mandate status at the CallBack Page
     * 
     * @return array $response
     */
    public static function verifyMandate()
    {
        $response = (\Request::query());

        if ($response['status'] != 'SUCCEED') {
            VandarMandate::where('token', $response['token'])
                ->update([
                    'errors' => json_encode('Failed To Access'),
                    'status' => 'FAILED'
                ]);

            # return 
            dd($response);
        }


        VandarMandate::where('token', $response['token'])
            ->update([
                'status' => 'SUCCEED',
                'is_active' => true,
                'authorization_id' => $response['authorization_id']
            ]);

        # return $response;
        dd($response);
    }



    /**
     * Send Request for Settlement
     *
     * @param string $url_param
     * @param array $params 
     */
    private static function request($method, $params = null, $url_param = null)
    {
        $access_token = VandarAuth::token();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
        ])->$method(self::MANDATE_BASE_URL($url_param), $params);

        return $response;
    }


    /**
     * Prepare Mandate Url for sending requests
     *
     * @param string|null $param
     * 
     * @return string $url
     */
    private static function MANDATE_BASE_URL(string $param = null)
    {
        return "https://api.vandar.io/v2/business/$_ENV[VANDAR_BUSINESS_NAME]/subscription/authorization/$param";
    }
}
