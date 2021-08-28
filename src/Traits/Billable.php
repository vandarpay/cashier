<?php

namespace Vandar\VandarCashier\Traits;

trait Billable
{
    public function vandar_payments()
    {
        return $this->morphMany(VandarPayment::class, 'billable');
    }
}
