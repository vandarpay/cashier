<?php

namespace Vandar\Cashier\Tests\Unit;

use Illuminate\Support\Facades\Event;
use Vandar\Cashier\Events\MandateCreating;
use Vandar\Cashier\Events\PaymentCreating;
use Vandar\Cashier\Events\WithdrawalCreating;
use Vandar\Cashier\Models\Mandate;
use Vandar\Cashier\Models\Payment;
use Vandar\Cashier\Models\Withdrawal;
use Vandar\Cashier\Tests\TestCase;

class ModelEventsTest extends TestCase
{
    public function test_can_emit_payment_creating_event()
    {
        Event::fake();

        factory(Payment::class)->create();

        Event::assertDispatched(PaymentCreating::class);
    }

    public function test_can_emit_mandate_creating_event()
    {
        Event::fake();

        factory(Mandate::class)->create();

        Event::assertDispatched(MandateCreating::class);
    }

    public function test_can_emit_withdrawal_creating_event()
    {
        Event::fake();

        factory(Withdrawal::class)->create();

        Event::assertDispatched(WithdrawalCreating::class);
    }
}