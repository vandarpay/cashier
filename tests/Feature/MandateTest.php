<?php

namespace Vandar\Cashier\Tests\Feature;

use Illuminate\Validation\ValidationException;
use Vandar\Cashier\Models\Mandate;
use Vandar\Cashier\Tests\Fixtures\User;
use Vandar\Cashier\Tests\TestCase;
class MandateTest extends TestCase
{
    public function test_can_create_mandate()
    {
        $user = factory(User::class)->create();
        /** @var Mandate $mandate */
        $mandate = factory(Mandate::class)->make();
        $mandate->mobile_number = config('vandar.mobile');

        try {
            $user->mandates()->save($mandate);
        } catch (ValidationException $exception){
            dump($exception->errors());
            $this->fail();
        }

        $this->assertEquals(1, $user->mandates()->count());
    }
}