<?php

namespace Vandar\Cashier\Tests\Feature;

use Vandar\Cashier\Exceptions\InvalidPayloadException;
use Vandar\Cashier\Models\Settlement;
use Vandar\Cashier\Tests\TestCase;

class SettlementTest extends TestCase
{
    public function test_can_create_settlement()
    {
        try {
            /** @var Settlement $settlement */
            $settlement = Settlement::query()->create(['amount' => 5000, 'iban' => env('VANDAR_TESTING_IBAN')]);
        } catch (InvalidPayloadException $exception) {
            dump($exception->errors());
            $this->fail();
        }

        $this->assertEquals('PENDING', $settlement->status);

        $settlement->cancel(); // To allow for multiple tests without running out of money
    }
}