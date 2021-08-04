<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Vandar\VandarCashier\Models\VandarWithdrawal;
use Vandar\VandarCashier\Utilities\VandarValidationRules;

class VandarWithdrawalController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;

    private $withdrawal_validation_rules;


    /**
     * Set related validation rules
     */

    public function __construct()
    {
        $this->withdrawal_validation_rules = VandarValidationRules::withdrawal();
    }



    /**
     * Store new withdrawal
     *
     * @param array $params
     * 
     * @return object $data
     */
    public function store($params)
    {
        $params['notify_url'] = $params['notify_url'] ?? $_ENV['VANDAR_NOTIFY_URL'];


        # Validate {params} by their rules
        $validator = Validator::make($params, $this->withdrawal_validation_rules['store']);

        # Show {error message} if there is any incompatibility with rules 
        if ($validator->fails())
            return $validator->errors()->messages();


        $response = $this->request('post', $this->WITHDRAWAL_URL('store'), true, $params);

        # prepare data for DB structure
        $data = $response->json()['result']['withdrawal'];

        $data['withdrawal_id'] = $data['id'];
        unset($data['id']);


        VandarWithdrawal::create((array)$data);


        return $data;
    }



    /**
     * Show the list of withdrawals
     *
     * @return object
     */
    public function list()
    {
        $response = $this->request('get', $this->WITHDRAWAL_URL(), true);

        return $response->json();
    }



    /**
     * Show the Deatils of stored withdrawals
     *
     * @param string $withdrawal_id
     * 
     * @return object
     */
    public function show(string $withdrawal_id)
    {
        $response = $this->request('get', $this->WITHDRAWAL_URL($withdrawal_id), true);

        return $response->json()['result']['withdrawal'];
    }



    /**
     * Cancel the stored withdrawals
     *
     * @param string $withdrawal_id
     * 
     * @return object
     */
    public function cancel(string $withdrawal_id)
    {
        $response = $this->request('put', $this->WITHDRAWAL_URL($withdrawal_id), true);

        VandarWithdrawal::where('withdrawal_id', $withdrawal_id)
            ->update(['status' => 'CANCELED']);


        return $response->json();
    }


    /**
     * Prepare Withdrawal Url for sending requests
     *
     * @param string|null $param
     * 
     * @return string $url
     */
    private function WITHDRAWAL_URL(string $param = null)
    {
        return "https://api.vandar.io/v2/business/$_ENV[VANDAR_BUSINESS_NAME]/subscription/withdrawal/$param";
    }
}
