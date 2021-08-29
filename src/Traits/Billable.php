<?php

namespace Vandar\Cashier\Traits;

use Vandar\Cashier\Models\Payment;

trait Billable
{
    public function vandar_payments()
    {
        return $this->morphMany(Payment::class, 'billable');
    }
}
