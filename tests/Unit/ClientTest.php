<?php

namespace Vandar\Cashier\Tests\Unit;

use Vandar\Cashier\Client\Client;
use Vandar\Cashier\Tests\TestCase;

class ClientTest extends TestCase
{

    public function test_can_get_json_response()
    {
        $response = Client::request('get', 'https://health.vandar.io/subscription', null, false);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsArray($response->json());
    }
}