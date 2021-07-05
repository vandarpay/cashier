<?php

namespace Vandar\VandarCashier\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VandarPayment extends Model
{
    protected $guarded =['id'];
    

    public function vandar_payable()
    {
        return $this->morphTo();
    }

    public function vandar_paymentable()
    {
        return $this->morphTo();
    }
}
