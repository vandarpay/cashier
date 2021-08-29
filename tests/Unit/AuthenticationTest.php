<?php

namespace Vandar\Cashier\Tests\Unit;

use Vandar\Cashier\Tests\TestCase;
use Vandar\Cashier\Vandar;

class AuthenticationTest extends TestCase
{
    public function test_can_authenticate()
    {
        $token = Vandar::Auth()->token();
        dump($token);
    }
}