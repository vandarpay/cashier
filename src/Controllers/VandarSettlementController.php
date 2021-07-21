<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SebastianBergmann\CodeCoverage\Report\PHP;
use Vandar\VandarCashier\Models\VandarSettlement;

class VandarSettlementController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;

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

        $response = self::request('post', self::SETTLEMENT_URL('store', 'v3'), true, $params);

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
        $response = self::request('get', self::SETTLEMENT_URL($settlement_id), true);

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
        $response = self::request('get', self::SETTLEMENT_URL(), true, $params);

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
        $response = self::request('delete', self::SETTLEMENT_URL($transaction_id), true);

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
