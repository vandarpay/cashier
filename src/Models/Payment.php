<?php

namespace Vandar\Cashier\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Payment extends Model
{
    protected $table = 'vandar_payments';
    protected $guarded =['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
