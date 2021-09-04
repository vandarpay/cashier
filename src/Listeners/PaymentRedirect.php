<?php

namespace Vandar\Cashier\Events;

class PaymentRedirect
{
    use \Vandar\Cashier\Events\PaymentCreating;

    public function handle(PaymentCreating $payment)
    {
    }
}
