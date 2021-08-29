<?php

namespace Vandar\VandarCashier\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'vandar_payments';
    protected $guarded =['id'];
    

    public function billable()
    {
        return $this->morphTo();
    }
    
}
