<?php

namespace Vandar\VandarCashier\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    protected $table = 'vandar_settlements';
    protected $guarded = ['id'];
}
