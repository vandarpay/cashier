<?php

namespace Vandar\Cashier\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Vandar\Cashier\Models\Withdrawal;

class WithdrawalCreating
{

    use Dispatchable, SerializesModels;

    /**
     * @var Withdrawal
     */
    public $withdrawal;


    public function __construct(Withdrawal $withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }
}
