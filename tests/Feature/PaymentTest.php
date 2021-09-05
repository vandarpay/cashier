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

        /** @var Payment $payment */
        $payment = factory(Payment::class)->make();
        $payment->valid_card_number = env('VANDAR_TESTING_VALID_CARD');
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