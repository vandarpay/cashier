<?php

namespace Vandar\Cashier\Tests\Unit;

use Illuminate\Support\Facades\Event;
use Vandar\Cashier\Models\Mandate;
use Vandar\Cashier\Models\Withdrawal;
use Vandar\Cashier\Tests\Fixtures\User;
use Vandar\Cashier\Tests\TestCase;
use function PHPUnit\Framework\assertEquals;

class UserWithdrawalRelationTest extends TestCase
{
    /**
     * @var User
     */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        Event::fake();
    }

    public function test_can_reach_withdrawal_through_user()
    {
        $mandate = $this->user->mandates()->save(factory(Mandate::class)->make());
        $mandate->withdrawals()->save(factory(Withdrawal::class)->make());
        assertEquals(1, $this->user->withdrawals()->count());
    }

    public function test_can_reach_withdrawal_through_withdrawal()
    {
        /** @var Mandate $mandate */
        $mandate = $this->user->mandates()->save(factory(Mandate::class)->make());
        /** @var Withdrawal $withdrawal */
        $withdrawal = $mandate->withdrawals()->save(factory(Withdrawal::class)->make());

        assertEquals($this->user->id, $withdrawal->user->id);
    }
}