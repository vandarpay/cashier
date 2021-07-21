<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vandar\VandarCashier\Models\VandarWithdrawal;

class VandarWithdrawalController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;

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

        $response = self::request('post', self::WITHDRAWAL_URL('store'), true, $params);

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
        $response = self::request('get', self::WITHDRAWAL_URL(), true);

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
        $response = self::request('get', self::WITHDRAWAL_URL($withdrawal_id), true);

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
        $response = self::request('put', self::WITHDRAWAL_URL($withdrawal_id), true);

        if ($response->status() != 200)
            dd($response->object()->errors ?? $response->object()->error);


        VandarWithdrawal::where('withdrawal_id', $withdrawal_id)
            ->update(['status' => 'CANCELED']);


        # return $response;
        dd($response->object());
    }


    /**
     * Prepare Withdrawal Url for sending requests
     *
     * @param string|null $param
     * 
     * @return string $url
     */
    private static function WITHDRAWAL_URL(string $param = null)
    {
        return "https://api.vandar.io/v2/business/$_ENV[VANDAR_BUSINESS_NAME]/subscription/withdrawal/$param";
    }
}
