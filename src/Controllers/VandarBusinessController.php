<?php

namespace Vandar\VandarCashier\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Vandar\VandarCashier\Utilities\VandarValidationRules;

class VandarBusinessController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;

    private $business_validation_rules;
    private $business_name;

    const BUSINESS_BASE_URL = 'https://api.vandar.io/v2/business/';


    /**
     * Set related validation rules
     */

    public function __construct()
    {
        $this->business_validation_rules = VandarValidationRules::business();
        $this->business_name = $_ENV['VANDAR_BUSINESS_NAME'];
    }


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
        $response = $this->request('get', $this->BUSINESS_URL($this->business_name), true);

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
        # Validate {params} and {morphs} by their rules
        $validator = Validator::make($params, $this->business_validation_rules['users']);
        
        # Show {error message} if there is any incompatibility with rules 
        if ($validator->fails())
        return $validator->errors()->messages();
        

        $response = $this->request('get', $this->BUSINESS_URL($this->business_name, '/iam'), true, $params);

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
