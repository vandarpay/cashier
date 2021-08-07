<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Vandar\VandarCashier\RequestsValidation\ListRequestValidation;

class VandarBusinessController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;

    const BUSINESS_BASE_URL = 'https://api.vandar.io/v2/business/';


    /**
     * Get the list of businesses
     *
     * @param string|null $business
     * 
     * @return array
     */
    public function list(string $business = null): array
    {
        $response = $this->request('get', $this->BUSINESS_URL($business ?? null), true);

        return $response->json();
    }



    /**
     * Get Business Information
     *
     * @param string|null $business
     * 
     * @return array 
     */
    public function info(string $business = null): array
    {
        $response = $this->request('get', $this->BUSINESS_URL($business ?? null), true);

        return $response->json();
    }



    /**
     * Get Business Users
     *
     * @param array|null $params [business, page, per_page]
     * 
     * @return array 
     */
    public function users(array $params = []): array
    {
        # Request Validation
        $business_request = new ListRequestValidation($params);
        $business_request->validate($business_request->rules());


        $response = $this->request('get', $this->BUSINESS_URL($params['business'] ?? null, '/iam'), true, $params);

        return $response->json();
    }


    /**
     * Business URL
     *
     * @param string|null $business
     * @param string|null $param
     * 
     * @return string
     */
    private function BUSINESS_URL(string $business = null, string $param = null): string
    {
        $business = $business ?? env('VANDAR_BUSINESS_NAME');
        return self::BUSINESS_BASE_URL . $business . $param;
    }
}
