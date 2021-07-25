<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VandarBillsController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;

    const BASE_BILLING_URL = 'https://api.vandar.io/v2/business/';

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
    public function list($params)
    {
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
