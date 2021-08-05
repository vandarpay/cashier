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
     * @return array
     */
    public function balance(): array
    {
        $response = $this->request('get', $this->BILLING_URL('balance'), true);

        return $response->json();
    }




    /**
     * Get Bills List
     *
     * @param array $params
     * 
     * @return array 
     */
    public function list(array $params = null): array
    {
        # Validate {params} by their rules
        $validator = Validator::make($params, $this->bills_validation_rules['list']);

        # Show {error message} if there is any incompatibility with rules 
        if ($validator->fails())
            return $validator->errors()->messages();


        $response = $this->request('get', $this->BILLING_URL('transaction'), true, $params);

        return $response->json();
    }



    /**
     * Billing URL
     *
     * @param string $param
     * 
     * @return string  
     */
    private function BILLING_URL(string $param): string
    {
        return self::BASE_BILLING_URL . env('VANDAR_BUSINESS_NAME') . "/$param";
    }
}
