<?php

namespace Vandar\Cashier\Listeners;

use Illuminate\Validation\ValidationException;
use Vandar\Cashier\Client\CasingFormatter;
use Vandar\Cashier\Client\Client;
use Vandar\Cashier\Events\MandateCreating;
use Vandar\Cashier\Models\Mandate;
use Vandar\Cashier\Vandar;

class SendMandateCreateRequest
{
    public function handle(MandateCreating $event)
    {
        $payload = $event->mandate->only(['bank_code', 'mobile_number', 'count', 'limit', 'name', 'email', 'expiration_date', 'wage_type']);
        $payload['callback_url'] = config('vandar.mandate_callback_url');
        $payload['expiration_date'] = $payload['expiration_date'] ?? date('Y-m-d', strtotime(date('Y-m-d') . ' + 3 years'));

        $payload = CasingFormatter::mobileKeyFormat($payload);
        $response = Client::request('post', Vandar::url('MANDATE_API', 'store'), $payload, true);

        if((! in_array($response->getStatusCode(), [200, 201])))
        {
            $event->mandate->status = Mandate::STATUS_FAILED;
            throw ValidationException::withMessages((array) $response->json()['errors']);

        }
        $event->mandate->token = $response->json()['result']['authorization']['token'];
    }
}
