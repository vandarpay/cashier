<?php

namespace Vandar\Cashier\Traits;

use Vandar\Cashier\Models\Payment;

trait Billable
{
    public function payments()
    {
        return $this->hasMany(Payment::class, 'billable');
    }
}
