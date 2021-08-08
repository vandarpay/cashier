<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Vandar\VandarCashier\Utilities\ParamsCaseFormat;
use Vandar\VandarCashier\RequestsValidation\BillsListRequestValidation;

class VandarBillsController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;

    private $bills_validation_rules;

    const BASE_BILLING_URL = 'https://api.vandar.io/v2/business/';


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
    public function list(array $params = []): array
    {
        # prepare data for send request
        $keys = ['from_date', 'to_date', 'status_kind'];
        $params = ParamsCaseFormat::convert($params, 'camel', $keys);

        # Request Validation
        $request = new BillsListRequestValidation($params);
        $request->validate($request->rules());

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
