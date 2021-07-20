<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Vandar\VandarCashier\VandarAuth;
use Vandar\VandarCashier\Models\VandarWithdrawal;

class VandarWithdrawalController extends Controller
{

    /**
     * Store new withdrawal
     *
     * @param array $params
     * 
     * @return object $data
     */
    public static function store($params)
    {
        $params['notify_url'] = $params['notify_url'] ?? $_ENV['VANDAR_NOTIFY_URL'];
        // dd($params);
        $response = self::request('post', 'store', $params);


        if ($response->status() != 200)
            dd($response->object()->errors ?? $response->object()->error);

        # prepare data for DB structure
        $data = $response->object()->result->withdrawal;

        $data->withdrawal_id = $data->id;
        unset($data->id);


        VandarWithdrawal::create((array)$data);


        # return $data;
        dd($data);
    }



    /**
     * Show the list of withdrawals
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
     * Show the Deatils of stored withdrawals
     *
     * @param string $withdrawal_id
     * 
     * @return object
     */
    public static function show($withdrawal_id)
    {
        $response = self::request('get', $withdrawal_id);

        if ($response->status() != 200)
            dd($response->object()->errors ?? $response->object()->error);

        # return $response->object()->result->withdrawal;
        dd($response->object()->result->withdrawal);
    }



    /**
     * Cancel the stored withdrawals
     *
     * @param string $withdrawal_id
     * 
     * @return object
     */
    public static function cancel($withdrawal_id)
    {
        $response = self::request('put', $withdrawal_id);


        if ($response->status() != 200)
            dd($response->object()->errors ?? $response->object()->error);


        VandarWithdrawal::where('withdrawal_id', $withdrawal_id)
            ->update(['status' => 'CANCELED']);


        # return $response;
        dd($response->object());
    }




    /**
     * Send Request for withdrawls
     *
     * @param string $method
     * @param string|null $url_param
     * @param string|null $params
     */
    private static function request($method, $url_param = null, $params = null)
    {
        $access_token = VandarAuth::token();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
            'Accept' => 'application/json',
        ])->$method(self::WITHDRAWAL_BASE_URL($url_param), $params);

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
        return "https://api.vandar.io/v2/business/$_ENV[VANDAR_BUSINESS_NAME]/subscription/withdrawal/$param";
    }
}
