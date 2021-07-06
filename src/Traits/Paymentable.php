<?php

namespace Vandar\VandarCashier\Traits;

trait Paymentable
{
    public function vandar_payments()
    {
        return $this->morphMany(VandarPayment::class, 'vandar_paymentable');
    }
}
