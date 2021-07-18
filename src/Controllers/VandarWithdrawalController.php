<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Vandar\VandarCashier\VandarAuth;
use Illuminate\Http\Request;

class VandarWithdrawalController extends Controller
{

    /**
     * Store new withdrawal
     *
     * @param array $params
     * 
     * @return object $response
     */
    public static function store($params)
    {
        $access_token = VandarAuth::token();
        $params['withdrawal_date'] = $params['withdrawal_date'] ?? date('Y-m-d', strtotime(date('Y-m-d')));

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
            'Accept' => 'application/json',
        ])->post(self::WITHDRAWAL_BASE_URL('/store'), [
            'authorization_id' => $params['authorization_id'],
            'amount' => $params['amount'],
            'withdrawal_date' => $params['withdrawal_date'],
            'is_instant' => $params['is_instant'],
            'notify_url' => $params['notify_url'] ?? $_ENV['VANDAR_NOTIFY_URL'],
            'max_retry_count' => $params['max_retry_count'] ?? 1,
        ]);


        # return OBJECT for errors
        if ($response->status() != 200)
            dd(json_decode($response)->errors ?? json_decode($response)->error);

        $response = json_decode($response);


        # return $response;
        dd($response);
    }



    /**
     * Show the list of withdrawals
     *
     * @return object $response
     */
    public static function list()
    {
        # send request
        $response = self::withdrawalRequest('get');


        if ($response->status() != 200)
            dd(json_decode($response)->errors ?? json_decode($response)->error);

        $response = json_decode($response);


        # return $response;
        dd($response);
    }



    /**
     * Show the Deatils of stored withdrawals
     *
     * @param string $withdrawal_id
     * 
     * @return object $response
     */
    public static function show($withdrawal_id)
    {
        # send request
        $response = self::withdrawalRequest('get', "/$withdrawal_id");

        if ($response->status() != 200)
            dd(json_decode($response)->errors ?? json_decode($response)->error);

        $response = json_decode($response);

        
        # return $response;
        dd($response);
    }



    /**
     * Cancel the stored withdrawals
     *
     * @param string $withdrawal_id
     * 
     * @return object $response
     */
    public static function cancel($withdrawal_id)
    {
        $access_token = VandarAuth::token();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
            'Accept' => 'application/json',
        ])->put(self::WITHDRAWAL_BASE_URL("/$withdrawal_id"));


        # return OBJECT for errors
        if ($response->status() != 200)
            dd(json_decode($response)->errors ?? json_decode($response)->error);

        $response = json_decode($response);

        # return $response;
        dd($response);
    }




    /**
     * Send Request for withdrawls
     *
     * @param string $method
     * @param string|null $url_param
     * @param string|null $params
     */
    private static function withdrawalRequest($method, $url_param = null, $params = null)
    {
        $access_token = VandarAuth::token();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
            'Accept' => 'application/json',
        ])->$method(self::WITHDRAWAL_BASE_URL($url_param));

        return $response;
    }



    /**
     * Prepare Withdrawal Url for sending requests
     *
     * @param string|null $param
     * 
     * @return string $url
     */
    private static function WITHDRAWAL_BASE_URL(string $param = null)
    {
        $url = "https://api.vandar.io/v2/business/$_ENV[VANDAR_BUSINESS_NAME]/subscription/withdrawal$param";
        return $url;
    }
}
