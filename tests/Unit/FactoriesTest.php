<?php

namespace Vandar\Cashier\Tests\Unit;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Event;
use Vandar\Cashier\Models\Mandate;
use Vandar\Cashier\Models\Payment;
use Vandar\Cashier\Models\Settlement;
use Vandar\Cashier\Models\Withdrawal;
use Vandar\Cashier\Tests\TestCase;

class FactoriesTest extends TestCase
{
    public function test_can_create_user()
    {
        $user = factory(User::class)->create();
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_can_create_payment()
    {
        Event::fake();
        $payment = factory(Payment::class)->create();
        $this->assertDatabaseHas('vandar_payments', ['id' => $payment->id]);
    }

    public function test_can_create_mandate()
    {
        Event::fake();
        $mandate = factory(Mandate::class)->create();
        $this->assertDatabaseHas('vandar_mandates', ['id' => $mandate->id]);
    }

    public function test_can_create_withdrawal()
    {
        Event::fake();
        $withdrawal = factory(Withdrawal::class)->create();
        $this->assertDatabaseHas('vandar_withdrawals', ['id' => $withdrawal->id]);
    }

    public function test_can_create_settlement()
    {
        Event::fake();
        $settlement = factory(Settlement::class)->create();
        $this->assertDatabaseHas('vandar_settlements', ['id' => $settlement->id]);
    }
}