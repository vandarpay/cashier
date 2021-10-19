<?php

namespace Vandar\Cashier\Listeners;

use Illuminate\Validation\ValidationException;
use Vandar\Cashier\Client\Client;
use Vandar\Cashier\Events\WithdrawalCreating;
use Vandar\Cashier\Models\Withdrawal;
use Vandar\Cashier\Vandar;

class SendWithdrawalCreateRequest
{

    public function handle(WithdrawalCreating $event)
    {
        $payload = $event->withdrawal->only(['authorization_id', 'amount', 'withdrawal_date', 'is_instant', 'max_retry_count', 'description']);
        $payload['notify_url'] = config('vandar.notify_url',  route('vandar.webhook.withdrawal'));
        $payload['withdrawal_date'] = $payload['withdrawal_date'] ?? date('Y-m-d');
        $payload['is_instant'] = (int) $payload['is_instant'];

        $response = Client::request('post', Vandar::url('WITHDRAWAL', 'store'), $payload, true);

        $event->withdrawal->status = $response->json()['result']['withdrawal']['status'];
        $event->withdrawal->gateway_transaction_id = $response->json()['result']['withdrawal']['gateway_transaction_id'];
        $event->withdrawal->withdrawal_id = $response->json()['result']['withdrawal']['id'];

        if(! $event->withdrawal->max_retry_count){
            $event->withdrawal->max_retry_count = 1;
        }
    }
}
