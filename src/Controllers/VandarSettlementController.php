<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use SebastianBergmann\CodeCoverage\Report\PHP;
use Vandar\VandarCashier\Models\VandarSettlement;
use Vandar\VandarCashier\VandarAuth;

class VandarSettlementController extends Controller
{

    /**
     * Store a new settlement
     *
     * @param  $params
     * 
     * @return array 
     */
    public static function store($params)
    {
        $params['notify_url'] = $params['notify_url'] ?? $_ENV['VANDAR_NOTIFY_URL'];
        VandarSettlement::create($params);
        $params['track_id'] = VandarSettlement::get('track_id')->last()['track_id'];
        $params['amount'] /= 10; // convert RIAL to TOMAN (for sending request)

        $response = self::request('post', $params, 'store', 'v3');

        if ($response->status() != 200)
            dd($response->object());


        # convert id to settlement_id for database compatible
        $data = $response->object()->data->settlement[0];


        $data->settlement_id = $data->id;
        $data->prediction = json_encode($data->prediction);
        unset($data->id);


        VandarSettlement::where('track_id', $params['track_id'])
            ->update((array)$data);

        dd($response->object()->data->settlement[0]);
        # return $response;
    }




    /**
     * Get Complete Details about a settlement
     *
     * @param int $settlement_id
     * 
     * @return array
     */
    public static function show($settlement_id)
    {
        $response = self::request('get', '', $settlement_id);


        if ($response->status() != 200)
            dd($response->object());


        # return $response->object()->data->settlement;
        dd($response->object()->data->settlement);
    }




    /**
     * Get the list of settlements
     *
     * @param array|null $params
     *
     * @return array
     */
    public static function list($params = null)
    {
        $response = self::request('get', $params);

        if ($response->status() != 200)
            dd($response->object());

        # return $response->object();
        dd($response->object()->data);
    }




    /**
     * Cancel the stored settlement 
     *
     * @param int $transaction_id
     * 
     * @return string
     */
    public static function cancel($transaction_id)
    {
        $response = self::request('delete', '', $transaction_id);

        if ($response->status() != 200) {
            VandarSettlement::where('transaction_id', $transaction_id)
                ->update([
                    'errors' => $response->object()->error
                ]);
            dd($response->object());
        }

        VandarSettlement::where('transaction_id', $transaction_id)
            ->update([
                'status' => 'CANCELED',
            ]);


        dd($response->object()->message);
    }




    /**
     * Send Request for Settlement
     *
     * @param string $url_param
     * @param array $params 
     */
    private static function request($method, $params = null, $url_param = null, $version = null)
    {
        $access_token = VandarAuth::token();

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
        ])->$method(self::SETTLEMENT_URL($url_param), $params);

        return $response;
    }




    /**
     * Prepare Settlement Url for sending request
     *
     * @param string $param
     * @param string $version
     * 
     * @return string 
     */
    private static function SETTLEMENT_URL($param = null, $version = 'v2.1')
    {
        return "https://api.vandar.io/$version/business/{$_ENV['VANDAR_BUSINESS_NAME']}/settlement/$param";
    }
}
