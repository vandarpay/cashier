<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Vandar\VandarCashier\Utilities\VandarValidationRules;

class VandarBusinessController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;

    private $business_validation_rules;

    const BUSINESS_BASE_URL = 'https://api.vandar.io/v2/business/';


    /**
     * Set related validation rules
     */
    public function __construct()
    {
        $this->business_validation_rules = VandarValidationRules::business();
    }


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
    public function users(array $params = null): array
    {
        # Validate {params} by their rules
        $validator = Validator::make($params, $this->business_validation_rules['users']);

        # Show {error message} if there is any incompatibility with rules 
        if ($validator->fails())
            return $validator->errors()->messages();


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
