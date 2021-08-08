<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Vandar\VandarCashier\Utilities\ParamsFormatConvertor;
use Vandar\VandarCashier\RequestsValidation\BillsListRequestValidation;

class VandarBillsController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;


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
        $params = ParamsFormatConvertor::caseFormat($params, 'camel', $keys);

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
        return config('vandar.api_base_url') . 'v2/business/' . config('vandar.business_name') . "/$param";
    }
}
