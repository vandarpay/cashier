<?php

namespace Vandar\VandarCashier\Controllers;

use Illuminate\Routing\Controller;
use Vandar\VandarCashier\Models\Settlement;
use Vandar\VandarCashier\RequestsValidation\ListRequestValidation;
use Vandar\VandarCashier\RequestsValidation\SettlementRequestValidation;

class VandarSettlementController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;


    /**
     * Store a new settlement
     *
     * @param  array $params
     * 
     * @return array 
     */
    public function store(array $params): array
    {
        $params['notify_url'] = $params['notify_url'] ?? config('vandar.notify_url');

        # Request Validation
        $request = new SettlementRequestValidation($params);
        $request->validate($request->rules());


        Settlement::create($params);
        $params['track_id'] = Settlement::get('track_id')->last()['track_id'];
        $params['amount'] /= 10; // convert RIAL to TOMAN (for sending request)


        $response = $this->request('post', $this->SETTLEMENT_URL('store', 'v3'), true, $params);

        # convert id to settlement_id for database compatible
        $db_data = $response->json()['data']['settlement'][0];

        
        $db_data['settlement_id'] = $db_data['id'];
        unset($db_data['id']);
        unset($db_data['prediction']);


        Settlement::where('track_id', $params['track_id'])
            ->update((array)$db_data);


        return $response->json();
    }




    /**
     * Get Complete Details about a settlement
     *
     * @param string $settlement_id
     * 
     * @return array
     */
    public function show(string $settlement_id): array
    {
        $response = $this->request('get', $this->SETTLEMENT_URL($settlement_id), true);

        return $response->json();
    }




    /**
     * Get the list of settlements
     *
     * @param array|null $params
     *
     * @return array
     */
    public function list(array $params = []): array
    {
        # Request Validation
        $request = new ListRequestValidation($params);
        $request->validate($request->rules());

        $response = $this->request('get', $this->SETTLEMENT_URL(), true, $params);

        return $response->json();
    }




    /**
     * Cancel the stored settlement 
     *
     * @param int $transaction_id
     * 
     * @return string
     */
    public function cancel(int $transaction_id): array
    {
        $response = $this->request('delete', $this->SETTLEMENT_URL($transaction_id), true);

        if ($response->status() != 200) {
            Settlement::where('transaction_id', $transaction_id)
                ->update([
                    'errors' => $response->object()->error
                ]);
            return $response->json();
        }

        Settlement::where('transaction_id', $transaction_id)
            ->update([
                'status' => 'CANCELED',
            ]);


        return $response->json();
    }



    /**
     * Prepare Settlement Url for sending request
     *
     * @param string|null $param
     * @param string $version
     * 
     * @return string 
     */
    private function SETTLEMENT_URL($param = null, $version = 'v2.1'): string
    {
        return config('vandar.api_base_url') . "$version/business/" . config('vandar.business_name') . "/settlement/$param";
    }
}
