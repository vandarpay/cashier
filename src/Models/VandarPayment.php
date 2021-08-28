<?php

namespace Vandar\VandarCashier\Models;

use Illuminate\Database\Eloquent\Model;

class VandarPayment extends Model
{
    protected $guarded =['id'];
    

    public function billable()
    {
        return $this->morphTo();
    }
    
}
