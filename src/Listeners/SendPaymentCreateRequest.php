<?php

namespace Vandar\Cashier\Listeners;

use Illuminate\Validation\ValidationException;
use Vandar\Cashier\Client\CasingFormatter;
use Vandar\Cashier\Client\Client;
use Vandar\Cashier\Events\PaymentCreating;
use Vandar\Cashier\Models\Payment;
use Vandar\Cashier\Vandar;

class SendPaymentCreateRequest
{
    public function handle(PaymentCreating $event)
    {
        $payload = $event->payment->only(['amount', 'mobile_number', 'factor_number', 'description', 'valid_card_number']);
        $payload['api_key'] = config('vandar.api_key');
        $payload['callback_url'] = config('vandar.callback_url');
        $payload['factorNumber'] = $payload['factor_number'];
        unset($payload['factor_number']);

        $payload = CasingFormatter::convertKeyFormat('camel', $payload, ['factor_number']);

        $response = Client::request('post', Vandar::url('IPG_API', 'send'), $payload, false);

        $event->payment->token = $response->json()['token'];
    }
}
