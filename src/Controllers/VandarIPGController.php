<?php

namespace Vandar\Cashier\Controllers;

use Illuminate\Routing\Controller;
use Vandar\Cashier\Client\CasingFormatter;
use Vandar\Cashier\Models\Payment;
use Vandar\Cashier\RequestsValidation\IPGRequestValidation;
use \Vandar\Cashier\Client\Client;
use Vandar\Cashier\Events\PaymentCreated;

class VandarIPGController extends Controller
{

    /**
     * Send payment payload, get token and redirect to payment page
     * 
     * @param array  $payload
     * @param string $callback_url
     */
    public function pay(array $payload)
    {
        $payload['api_key'] = config('vandar.api_key');


        # Client Validation
        $ipg_request = new IPGRequestValidation($payload);
        $ipg_request->validate($ipg_request->rules());


        $payload = CasingFormatter::convertKeyFormat('camel', $payload, ['factor_number']);

        $response = Client::request('post', $this->IPG_URL('send'), $payload, false);

        if ($response->getStatusCode() != 200)
            return $response->json()['errors'];

        $payment = Payment::create($payload);

        event(new PaymentCreated($payment));
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
