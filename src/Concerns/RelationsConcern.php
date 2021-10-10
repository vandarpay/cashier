<?php

namespace Vandar\Cashier\Concerns;

use Vandar\Cashier\Models\Mandate;
use Vandar\Cashier\Models\Payment;
use Vandar\Cashier\Models\Withdrawal;

trait RelationsConcern
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
        return $this->hasManyThrough(Withdrawal::class, Mandate::class, 'user_id', 'authorization_id', 'id', 'authorization_id');
    }
}