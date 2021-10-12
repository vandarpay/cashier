<?php

namespace Vandar\Cashier\Tests\Unit;

use Illuminate\Support\Facades\Event;
use Vandar\Cashier\Client\Client;
use Vandar\Cashier\Models\Withdrawal;
use Vandar\Cashier\Tests\TestCase;

class WebhookTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
        Event::fake();
    }

    public function test_can_update_withdrawal_on_notification()
    {
        $withdrawal = factory(Withdrawal::class)->make();
        $withdrawal->status = Withdrawal::STATUS_PENDING;
        $withdrawal->save();

        $this->post(route('vandar.webhook.withdrawal'), [
            'withdrawal_id' => $withdrawal->withdrawal_id,
            'authorization_id' => $withdrawal->authorization_id,
            'gateway_transaction_id' => 'weawea',
            'status' => Withdrawal::STATUS_DONE,
            'payment_number' => 1,
        ]);

        $this->assertDatabaseHas('vandar_withdrawals', ['id' => $withdrawal->id, 'status' => Withdrawal::STATUS_DONE]);
    }
}