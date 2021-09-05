<?php

namespace Vandar\Cashier\Tests\Feature;

use Illuminate\Validation\ValidationException;
use Vandar\Cashier\Tests\Fixtures\User;
use Vandar\Cashier\Models\Payment;
use Vandar\Cashier\Tests\TestCase;

class PaymentTest extends TestCase
{
    public function test_can_create_payment_with_relation_to_user()
    {
        $user = factory(User::class)->create();
        $payment = factory(Payment::class)->make();
        try {
            $user->payments()->save($payment);
        } catch (ValidationException $exception)
        {
            dump($exception->errors());
            $this->fail();
        }

        $this->assertEquals(1, $user->payments()->count());
    }
}