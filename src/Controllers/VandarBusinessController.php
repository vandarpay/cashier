<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VandarBusinessController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;

    const BUSINESS_BASE_URL = 'https://api.vandar.io/v2/business/';

    /**
     * Get the list of businesses
     *
     * @return array
     */
    public function list()
    {
        $response = $this->request('get', $this->BUSINESS_URL(), true);

        return $response->json()['data'];
    }



    /**
     * Get Business Information
     *
     * @param string|null $business
     * 
     * @return object $business_info
     */
    public function info()
    {
        $response = $this->request('get', $this->BUSINESS_URL($_ENV['VANDAR_BUSINESS_NAME']), true);

        return $response->json()['data'];
    }



    /**
     * Get Business Users
     *
     * @param string|null $business
     * 
     * @return object 
     */
    public function users($params = null)
    {
        $response = $this->request('get', $this->BUSINESS_URL($_ENV['VANDAR_BUSINESS_NAME'], '/iam'), true, $params);

        return $response->json()['data'];
    }


    /**
     * Business URL
     *
     * @param string|null $business
     * @param string|null $param
     * 
     * @return string
     */
    private function BUSINESS_URL(string $business = null, string $param = null)
    {
        return self::BUSINESS_BASE_URL . $business . $param;
    }
}
