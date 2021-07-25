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
    public function store($params)
    {
        $params['notify_url'] = $params['notify_url'] ?? $_ENV['VANDAR_NOTIFY_URL'];
        VandarSettlement::create($params);
        $params['track_id'] = VandarSettlement::get('track_id')->last()['track_id'];
        $params['amount'] /= 10; // convert RIAL to TOMAN (for sending request)

        $response = $this->request('post', $this->SETTLEMENT_URL('store', 'v3'), true, $params);

        # convert id to settlement_id for database compatible
        $data = $response->json()['data']['settlement'][0];


        $data['settlement_id'] = $data['id'];
        $data['prediction'] = json_encode($data['prediction']);
        unset($data['id']);


        VandarSettlement::where('track_id', $params['track_id'])
            ->update((array)$data);

        return $response->json()['data']['settlement'];
    }




    /**
     * Get Complete Details about a settlement
     *
     * @param int $settlement_id
     * 
     * @return array
     */
    public function show($settlement_id)
    {
        $response = $this->request('get', $this->SETTLEMENT_URL($settlement_id), true);

        return $response->json()['data']['settlement'];
    }




    /**
     * Get the list of settlements
     *
     * @param array|null $params
     *
     * @return array
     */
    public function list($params = null)
    {
        $response = $this->request('get', $this->SETTLEMENT_URL(), true, $params);

        return $response->json()['data'];
    }




    /**
     * Cancel the stored settlement 
     *
     * @param int $transaction_id
     * 
     * @return string
     */
    public function cancel($transaction_id)
    {
        $response = $this->request('delete', $this->SETTLEMENT_URL($transaction_id), true);

        if ($response->status() != 200) {
            VandarSettlement::where('transaction_id', $transaction_id)
                ->update([
                    'errors' => $response->object()->error
                ]);
            return $response->json();
        }

        VandarSettlement::where('transaction_id', $transaction_id)
            ->update([
                'status' => 'CANCELED',
            ]);


        return $response->json()['message'];
    }



    /**
     * Prepare Settlement Url for sending request
     *
     * @param string $param
     * @param string $version
     * 
     * @return string 
     */
    private function SETTLEMENT_URL($param = null, $version = 'v2.1')
    {
        return "https://api.vandar.io/$version/business/{$_ENV['VANDAR_BUSINESS_NAME']}/settlement/$param";
    }
}
