<?php

namespace Vandar\VandarCashier\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Vandar\VandarCashier\Models\VandarMandate;
use Vandar\VandarCashier\Utilities\VandarValidationRules;

class VandarMandateController extends Controller
{
    use \Vandar\VandarCashier\Utilities\Request;


    private $mandate_validation_rules;

    const MANDATE_REDIRECT_URL = 'https://subscription.vandar.io/authorizations/';


    /**
     * Set related validation rules
     */

    public function __construct()
    {
        $this->mandate_validation_rules = VandarValidationRules::mandate();
    }



    /**
     * Get the list of confirmed Mandates
     *
     * @return array
     */
    public function list(): array
    {
        $response = $this->request('get', $this->MANDATE_URL(), true);

        return $response->json();
    }



    /**
     * Store new Mandate
     *
     * @param array $params
     */
    public function store(array $params)
    {
        $params['expiration_date'] = $params['expiration_date'] ?? date('Y-m-d', strtotime(date('Y-m-d') . ' + 3 years'));
        $params['callback_url'] = $params['callback_url'] ?? env('VANDAR_CALLBACK_URL');


        # Validate {params} by their rules
        $validator = Validator::make($params, $this->mandate_validation_rules['store']);

        # Show {error message} if there is any incompatibility with rules 
        if ($validator->fails())
            return $validator->errors()->messages();


        $response = $this->request('post', $this->MANDATE_URL('store'), true, $params);


        $params['token'] = $response->json()['result']['authorization']['token'];


        VandarMandate::create($params);


        return Redirect::away(self::MANDATE_REDIRECT_URL . $params['token']);
    }



    /**
     * Show the mandate details
     *
     * @param string $authorization_id
     * 
     * @return array
     */
    public function show(string $authorization_id): array
    {
        $response = $this->request('get', $this->MANDATE_URL($authorization_id), true);

        return $response->json();
    }


    /**
     * Revoke Confirmed mandates
     *
     * @param string $authorization_id
     * 
     * @return array
     */
    public function revoke(string $authorization_id): array
    {
        $response = $this->request('delete', $this->MANDATE_URL($authorization_id), true);

        VandarMandate::where('authorization_id', $authorization_id)
            ->update(['is_active' => false]);

        return $response->json();
    }


    /**
     * Prepare Mandate Url for sending requests
     *
     * @param string|null $param
     * 
     * @return string 
     */
    private function MANDATE_URL(string $param = null): string
    {
        return 'https://api.vandar.io/v2/business/' . env('VANDAR_BUSINESS_NAME') . '/subscription/authorization/' . $param;
    }
}
