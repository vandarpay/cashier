<?php

namespace Vandar\VandarCashier\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VandarPayment extends Model
{


    public function vandar_paymentable()
    {
        return $this->morphTo();
    }
}
