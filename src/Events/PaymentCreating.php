<?php

namespace Vandar\Cashier\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Vandar\Cashier\Models\Payment;

class PaymentCreating
{
    use Dispatchable, SerializesModels;

    public $payment;


    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}