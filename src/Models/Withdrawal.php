<?php

namespace Vandar\Cashier\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $table = 'vandar_withdrawals';
    protected $guarded = ['id'];
}
