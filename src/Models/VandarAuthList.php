<?php

namespace Vandar\VandarCashier\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VandarAuthList extends Model
{
    protected $table = "vandar_auth_list";

    protected $fillable = [
        'token_type', 'expires_in', 'access_token', 'refresh_token'
    ];
}
