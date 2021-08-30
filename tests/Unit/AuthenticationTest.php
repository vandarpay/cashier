<?php

namespace Vandar\Cashier\Tests\Unit;

use Vandar\Cashier\Client\Authenticate;
use Vandar\Cashier\Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_can_authenticate()
    {
        $this->assertNotNull(Authenticate::getToken());
    }
}