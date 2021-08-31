<?php

namespace Vandar\Cashier\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Vandar\Cashier\Client\CasingFormatter;
use Vandar\Cashier\Models\Payment;
use Vandar\Cashier\RequestsValidation\IPGRequestValidation;
use \Vandar\Cashier\Client\Client;

class VandarIPGController extends Controller
{

    /**
     * Send payment payload, get token and redirect to payment page
     * 
     * @param array  $payload
     * @param string $callback_url
     */
    public function pay(array $payload, string $callback_url)
    {
        $payload['callback_url'] = $callback_url;
        $payload['api_key'] = config('vandar.api_key');

        
        # Client Validation
        $ipg_request = new IPGRequestValidation($payload);
        $ipg_request->validate($ipg_request->rules());


        $payload = CasingFormatter::convertKeyFormat('camel', $payload, ['factor_number']);
        
        $response = Client::request('post', $this->IPG_URL('send'), $payload, false);


        Payment::create($payload);


        return Redirect::away($this->REDIRECT_URL($response->json()['token']));
    }




    /**
     * Verify the all transaction by sending {TOKEN & API_KEY} 
     *
     * @return array 
     */
    public function verifyTransaction(string $payment_token): array
    {
        $params = ['api_key' => config('vandar.api_key'), 'token' => $payment_token];

        $response = Client::request('post', $this->IPG_URL('verify'), $params, false);

        if ($response->getStatusCode() != 200) {
            Payment::where('token', $payment_token)
                ->update([
                    'errors' => json_encode($response->json()['errors']),
                    'status' => 'FAILED'
                ]);
            return $response->json();
        }


        # prepare response for making compatible with DB
        $response = CasingFormatter::convertKeysToSnake($response->json());
        $response = CasingFormatter::mobileKeyFormat($response);

        $response['status'] = 'SUCCEED';

        Payment::where('token', $payment_token)
            ->update($response);

        return $response;
    }



    /**
     * Make proper IPG Url for sending requests
     *
     * @param string|null $param
     * 
     * @return string 
     */
    private function IPG_URL(string $url_param): string
    {
        return config('vandar.ipg_base_url') . 'api/v3/' . $url_param;
    }


    /**
     * Make proper Redirect Url for payment page
     *
     * @param string $token
     * 
     * @return string
     */
    private function REDIRECT_URL($token): string
    {
        return config('vandar.ipg_base_url') . 'v3/' . $token;
    }
}
