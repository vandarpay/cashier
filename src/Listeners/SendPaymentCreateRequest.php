<?php

namespace Vandar\Cashier\Listeners;

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

        $payload = CasingFormatter::convertKeyFormat('camel', $payload, ['factor_number']);

        $response = Client::request('post', Vandar::url('IPG_API', 'send'), $payload, false)->json();

        if(! in_array($response->getStatusCode(), [200, 201]) || $response['status'] !== 1)
        {
            $event->payment->update(['status' => Payment::STATUS_FAILED]);
            // TODO throw custom failure exception
        }

        $event->payment->update(['status' => Payment::STATUS_SUCCEED, 'token' => $response['token']]);
    }
}
