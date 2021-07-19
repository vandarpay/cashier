<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vandar\VandarCashier\VandarAuth;
use Illuminate\Support\Facades\Http;
use Vandar\VandarCashier\Models\VandarSubscription;

class VandarMandateController extends Controller
{
    const REDIRECT_URL = 'https://subscription.vandar.io/authorizations/';


    /**
     * Undocumented function
     *
     * @return void
     */
    public static function list()
    {
        $access_token = VandarAuth::token();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
            'Accept' => 'application/json',
        ])->get(self::SUBSCRIPTION_BASE_URL());

        # return OBJECT for errors
        if ($response->status() != 200)
            dd(json_decode($response)->errors ?? json_decode($response)->error);

        $response = json_decode($response);

        dd($response);
    }



    /**
     * Undocumented function
     *
     * @param [type] $params
     * @return void
     */
    public static function store($params)
    {
        $access_token = VandarAuth::token();
        $params['expiration_date'] = $params['expiration_date'] ?? date('Y-m-d', strtotime(date('Y-m-d') . ' + 3 years'));

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
            'Accept' => 'application/json',
        ])->post(self::SUBSCRIPTION_BASE_URL('/store'), [
            'bank_code' => $params['VANDAR_BANK_CODE'],
            'mobile' => $params['mobile'],
            'callback_url' => $params['callback_url'] ?? $_ENV['VANDAR_CALLBACK_URL'],
            'count' => $params['count'],
            'limit' => $params['limit'],
            'name' => $params['name'] ?? NULL,
            'email' => $params['email'] ?? NULL,
            'expiration_date' => $params['expiration_date'],
            'wage_type' => $_ENV['VANDAR_WAGE_TYPE'] ?? NULL,
        ]);


        # return OBJECT for errors
        if ($response->status() != 200)
            dd(json_decode($response)->errors ?? json_decode($response)->error);

        $response = json_decode($response);


        $token = $response->result->authorization->token;

        $params['token'] = $token;
        $params['bank_code'] = $_ENV['VANDAR_BANK_CODE'];
        VandarSubscription::create($params);


        # TODO => check Redirection (check "return")
        dd(self::REDIRECT_URL . $token);
        // return redirect()->away(self::REDIRECT_URL . $token);
        // return redirect(self::REDIRECT_URL . $token);
    }



    /**
     * Undocumented function
     *
     * @param [type] $subscription_code
     * @return void
     */
    public static function show($subscription_code)
    {
        $access_token = VandarAuth::token();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
            'Accept' => 'application/json',
        ])->get(self::SUBSCRIPTION_BASE_URL("/$subscription_code"));

        # return OBJECT for errors
        if ($response->status() != 200)
            dd(json_decode($response)->errors ?? json_decode($response)->error);

        $response = json_decode($response);

        # return $response;
        dd($response);
    }


    /**
     * Undocumented function
     *
     * @param [type] $subscription_code
     * @return void
     */
    public static function revoke($subscription_code)
    {
        $access_token = VandarAuth::token();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
            'Accept' => 'application/json',
        ])->delete(self::SUBSCRIPTION_BASE_URL("/$subscription_code"));


        # return OBJECT for errors
        if ($response->status() != 200)
            dd(json_decode($response)->errors ?? json_decode($response)->error);

        $response = json_decode($response);

        // VandarSubscription::where('subscription_code', $subscription_code)
        // ->update(['is_active' => false]);

        # return $response;
        dd($response);
    }




    /**
     * Prepare Subscription Url for sending requests
     *
     * @param string|null $param
     * 
     * @return string $url
     */
    public static function SUBSCRIPTION_BASE_URL(string $param = null)
    {
        $url = "https://api.vandar.io/v2/business/$_ENV[VANDAR_BUSINESS_NAME]/subscription/authorization$param";
        return $url;
    }




    /**
     * Check the Subscription status at the {CallBack Page}
     *
     */
    public static function verifySubscription()
    {
        $response = (\Request::query());

        // dd(json_decode($response));

        # TODO => update Subscription status in database
        if ($response['status'] != 'SUCCEED') {

            // VandarSubscription::where('token', $response['token'])
            //     ->update([
            //         'errors' => 'failed payment',
            //         'status' => 'FAILED'
            //     ]);


            echo "فرایند پرداخت با خطا مواجه شد <br> لطفا مجدداً تلاش کنید";

            return;
        }
        dd($response);
        // return self::verifyTransaction($response['token']);
    }
}
