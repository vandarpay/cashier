<?php

namespace Vandar\Cashier\Events;

use Vandar\Cashier\Models\Payment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class PaymentCreated{

    use Dispatchable, SerializesModels;

    public $payment;

    
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}