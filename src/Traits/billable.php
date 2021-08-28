<?php

namespace Vandar\VandarCashier\Traits;

trait billable
{
    public function vandar_payments()
    {
        return $this->morphMany(VandarPayment::class, 'billable');
    }
}
