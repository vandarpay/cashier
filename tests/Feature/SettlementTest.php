<?php

namespace Vandar\Cashier\Tests\Feature;

use Vandar\Cashier\Exceptions\InvalidPayloadException;
use Vandar\Cashier\Models\Settlement;
use Vandar\Cashier\Tests\TestCase;

class SettlementTest extends TestCase
{
    public function test_can_create_settlement()
    {
        $amount_error = ['amount' => [
            'عدم کفایت موجودی (کسری موجودی)',
            'مبلغ مورد نظر برای تسویه بیشتر از موجودی شما می باشد'
        ]];

        try {
            /** @var Settlement $settlement */
            $settlement = Settlement::query()->create(['amount' => 5000, 'iban' => env('VANDAR_TESTING_IBAN')]);
        } catch (InvalidPayloadException $exception) {
            if($exception->errors()['errors'] == $amount_error) { // In case the testing business has run out of balance
                return;
            }
            dump($exception->errors());
            $this->fail();
        }

        $this->assertEquals('PENDING', $settlement->status);

        $settlement->cancel(); // To allow for multiple tests without running out of money
    }
}