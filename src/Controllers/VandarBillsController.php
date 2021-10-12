<?php

namespace Vandar\Cashier\Controllers;

use Illuminate\Routing\Controller;
use Vandar\Cashier\RequestsValidation\BillsListRequestValidation;
use Vandar\Cashier\Utilities\Client;
use Vandar\Cashier\Utilities\ParamsFormatConvertor;

class VandarBillsController extends Controller
{


    /**
     * Get Wallet Balance
     *
     * @return array
     */
    public function balance(): array
    {
        $response = Client::request('get', $this->BILLING_URL('balance'), true);

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

        # Client Validation
        $request = new BillsListRequestValidation($params);
        $request->validate($request->rules());

        $response = Client::request('get', $this->BILLING_URL('transaction'), true, $params);

        return $response->json();
    }
}
