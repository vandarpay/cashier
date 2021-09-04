<?php

namespace Vandar\Cashier\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Vandar\Cashier\Models\Withdrawal;

class WithdrawalCreating{

    use Dispatchable, SerializesModels;

    public $withdrawal;

    
    public function __construct(Withdrawal $withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }
}