<?php

namespace Vandar\VandarCashier\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VandarWithdrawal extends Model
{
    protected $table = 'vandar_withdrawals';
    protected $guarded = ['id'];
}
