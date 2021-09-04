<?php

namespace Vandar\Cashier\Events;


class WithdrawalResponse
{
    use \Vandar\Cashier\Events\WithdrawalCreating;

    public function handle(WithdrawalCreating $payment)
    {
        
    }
}
