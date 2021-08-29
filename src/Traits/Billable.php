<?php

namespace Vandar\VandarCashier\Traits;

use Vandar\VandarCashier\Models\VandarPayment;

trait Billable
{
    public function vandar_payments()
    {
        return $this->morphMany(VandarPayment::class, 'billable');
    }
}
