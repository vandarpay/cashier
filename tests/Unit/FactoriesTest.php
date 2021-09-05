<?php

namespace Vandar\Cashier\Tests\Unit;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Event;
use Vandar\Cashier\Models\Payment;
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
}