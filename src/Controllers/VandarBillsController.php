<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Vandar\VandarCashier\Utilities\VandarValidationRules;

class VandarBillsController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;

    private $bills_validation_rules;
    const BASE_BILLING_URL = 'https://api.vandar.io/v2/business/';


    /**
     * Set related validation rules
     */
     
    public function __construct()
    {
        $this->bills_validation_rules = VandarValidationRules::bills();
    }


    /**
     * Get Wallet Balance
     *
     * @return array $data
     */
    public function balance()
    {
        $response = $this->request('get', $this->BILLING_URL('balance'), true);

        return $response->json()['data'];
    }




    /**
     * Get Bills List
     *
     * @return array $data
     */
    public function list($params = null)
    {
        # Validate {params} and {morphs} by their rules
        $validator = Validator::make($params, $this->bills_validation_rules['list']);

        # Show {error message} if there is any incompatibility with rules 
        if ($validator->fails())
            return $validator->errors()->messages();


        $response = $this->request('get', $this->BILLING_URL('transaction'), true, $params);

        return $response->json()['data'];
    }



    /**
     * Billing URL
     *
     * @param string $param
     * 
     * @return string  
     */
    private function BILLING_URL(string $param)
    {
        return self::BASE_BILLING_URL . $_ENV['VANDAR_BUSINESS_NAME'] . "/$param";
    }
}
