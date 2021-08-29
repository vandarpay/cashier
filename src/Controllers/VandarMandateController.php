<?php

namespace Vandar\Cashier\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Vandar\Cashier\Models\Mandate;
use Vandar\Cashier\RequestsValidation\MandateRequestValidation;
use Vandar\Cashier\Utilities\ParamsFormatConvertor;

class VandarMandateController extends Controller
{
    use \Vandar\Cashier\Utilities\Request;

    const MANDATE_REDIRECT_URL = 'https://subscription.vandar.io/authorizations/';


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
        $params['callback_url'] = $params['callback_url'] ?? config('vandar.callback_url');

        
        # Request Validation
        $request = new MandateRequestValidation($params);
        $request->validate($request->rules());
        
        $newParams = ParamsFormatConvertor::mobileFormat($params);
        
        
        $response = $this->request('post', $this->MANDATE_URL('store'), true, $newParams);
        
        
        $params['token'] = $response->json()['result']['authorization']['token'];
        

        Mandate::create($params);


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

        Mandate::where('authorization_id', $authorization_id)
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
        return config('vandar.api_base_url') . 'v2/business/' . config('vandar.business_name') . '/subscription/authorization/' . $param;
    }

}
