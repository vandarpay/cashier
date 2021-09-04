<?php

namespace Vandar\Cashier\Events;

use Illuminate\Support\Facades\Redirect;

class PaymentRedirect
{
    use \Vandar\Cashier\Vandar;
    use \Vandar\Cashier\Events\PaymentCreating;

    public function handle(PaymentCreating $payment)
    {
        return Redirect::away($this->IPG_BASE_URL . $this->API_VERSIONS['IPG'] . "/$payment->token");
    }
}
