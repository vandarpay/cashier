<?php

namespace Vandar\Cashier\Traits;

use Vandar\Cashier\Models\Mandate;
use Vandar\Cashier\Models\Payment;
use Vandar\Cashier\Models\Withdrawal;

trait Billable
{
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }


    public function mandates()
    {
        return $this->hasMany(Mandate::class);
    }


    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }


    public function isValidMandate()
    {
        if ($this->mandates()->where('expiration_date', '>', date('Y-m-d'))->exists())
            return true;

        return false;
    }
}
