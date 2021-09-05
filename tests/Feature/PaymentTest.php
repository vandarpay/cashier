<?php

namespace Vandar\Cashier\Tests\Feature;

use Vandar\Cashier\Tests\Fixtures\User;
use Vandar\Cashier\Models\Payment;
use Vandar\Cashier\Tests\TestCase;

class PaymentTest extends TestCase
{
    public function test_can_create_payment()
    {
        $user = factory(User::class)->create();
        $payment = factory(Payment::class)->make();
        $user->payments()->save($payment);

        $this->assertEquals(1, $user->payments()->count());
    }
}