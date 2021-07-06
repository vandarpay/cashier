<?php

namespace Vandar\VandarCashier\Traits;

trait Payable
{
    public function vandar_payments()
    {
        return $this->morphMany(VandarPayment::class, 'vandar_payable');
    }
}
