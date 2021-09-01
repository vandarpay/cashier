<?php

namespace Vandar\Cashier\Models;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    protected $table = 'vandar_settlements';
    protected $guarded = ['id'];
}
