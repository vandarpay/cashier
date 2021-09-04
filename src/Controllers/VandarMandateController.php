<?php

namespace Vandar\Cashier\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Vandar\Cashier\Models\Mandate;
use Vandar\Cashier\RequestsValidation\MandateRequestValidation;
use Vandar\Cashier\Client\CasingFormatter;
use Vandar\Cashier\Client\Client;

class VandarMandateController extends Controller
{

    const MANDATE_REDIRECT_URL = 'https://subscription.vandar.io/authorizations/';


    /**
     * Get the list of confirmed Mandates
     *
     * @return array
     */
    public function list(): array
    {
        $response = Client::request('get', $this->MANDATE_URL(), [], true);

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


        # Client Validation
        $request = new MandateRequestValidation($params);
        $request->validate($request->rules());

        $newParams = CasingFormatter::mobileKeyFormat($params);


        $response = Client::request('post', $this->MANDATE_URL('store'), $newParams, true);

        if ($response->getStatusCode() != 200)
            return $response->json()['errors'];

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
        $response = Client::request('get', $this->MANDATE_URL($authorization_id), [], true);

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
        $response = Client::request('delete', $this->MANDATE_URL($authorization_id), [], true);

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
