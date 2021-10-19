<?php

namespace Vandar\Cashier\Listeners;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Vandar\Cashier\Client\Client;
use Vandar\Cashier\Events\SettlementCreating;
use Vandar\Cashier\Vandar;

class SendSettlementCreateRequest
{

    public function handle(SettlementCreating $event)
    {
        $payload = $event->settlement->only(['amount', 'iban', 'track_id', 'payment_number']);
        $payload['notify_url'] = config('vandar.settlement_notify_url',  route('vandar.webhook.settlement'));
        $payload['track_id'] = $payload['track_id'] ?? Str::uuid()->toString();

        $response = Client::request('post', Vandar::url('SETTLEMENT', 'store'), $payload, true);

        if ((!in_array($response->getStatusCode(), [200, 201]))) {
            $event->settlement->status = 'FAILED';
            throw ValidationException::withMessages((array)$response->json()['errors']);

        }

        // Update model attributes that depend on Vandar response
        // Note: use of update method when in creating stage does not function correctly
        $data = $response->json()['data']['settlement'][0];
        $event->settlement->iban_id = $data['iban_id'];
        $event->settlement->transaction_id = $data['transaction_id'];
        $event->settlement->amount_toman = $data['amount_toman'];
        $event->settlement->wage_toman = $data['wage_toman'];
        $event->settlement->wallet = $data['wallet'];
        $event->settlement->settlement_date = $data['settlement_date'];
        $event->settlement->settlement_time = $data['settlement_time'];
        $event->settlement->settlement_date_jalali = $data['settlement_date_jalali'];
        $event->settlement->status = $data['status'];
        $event->settlement->track_id = $payload['track_id'];
    }
}
