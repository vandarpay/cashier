<?php

namespace Vandar\VandarCashier\Models;

use Illuminate\Database\Eloquent\Model;

class VandarPayment extends Model
{
    protected $table = 'vandar_payments';
    protected $guarded =['id'];
    

    public function billable()
    {
        return $this->morphTo();
    }
    
}
