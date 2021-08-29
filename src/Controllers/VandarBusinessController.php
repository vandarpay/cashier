<?php

namespace Vandar\Cashier\Controllers;

use Illuminate\Routing\Controller;
use Vandar\Cashier\RequestsValidation\ListRequestValidation;

class VandarBusinessController extends Controller
{
    use \Vandar\Cashier\Utilities\Request;


    /**
     * Get the list of businesses
     *
     * @param string|null $business
     * 
     * @return array
     */
    public function list(): array
    {
        $response = $this->request('get', $this->BUSINESS_URL(), true);

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
        $response = $this->request('get', $this->BUSINESS_URL($business ?? config('vandar.business_name')), true);

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

        $business = $params['business'] ?? config('vandar.business_name');
        unset($params['business']);

        $response = $this->request('get', $this->BUSINESS_URL($business, '/iam'), true, $params);

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
        return config('vandar.api_base_url') . 'v2/business/' . $business . $param;
    }
}
