<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Vandar\VandarCashier\Models\VandarSettlement;
use Vandar\VandarCashier\Utilities\VandarValidationRules;

class VandarSettlementController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;

    private $settlement_validation_rules;



    /**
     * Set related validation rules
     */
    public function __construct()
    {
        $this->settlement_validation_rules = VandarValidationRules::settlement();
    }


    /**
     * Store a new settlement
     *
     * @param  array $params
     * 
     * @return array 
     */
    public function store(array $params): array
    {
        $params['notify_url'] = $params['notify_url'] ?? env('VANDAR_NOTIFY_URL');


        # Validate {params} by their rules
        $validator = Validator::make($params, $this->settlement_validation_rules['store']);

        # Show {error message} if there is any incompatibility with rules 
        if ($validator->fails())
            return $validator->errors()->messages();


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
    public function list(array $params = null): array
    {
        # Validate {params} by their rules
        $validator = Validator::make($params, $this->settlement_validation_rules['list']);

        # Show {error message} if there is any incompatibility with rules 
        if ($validator->fails())
            return $validator->errors()->messages();


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
        return "https://api.vandar.io/$version/business/{$_ENV['VANDAR_BUSINESS_NAME']}/settlement/$param";
    }
}
