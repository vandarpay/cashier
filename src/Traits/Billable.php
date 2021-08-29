<?php

namespace Vandar\VandarCashier\Traits;

use Vandar\VandarCashier\Models\Payment;

trait Billable
{
    public function vandar_payments()
    {
        return $this->morphMany(Payment::class, 'billable');
    }
}
