<?php

namespace Vandar\Cashier\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Model;
use Vandar\Cashier\Traits\Billable;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use Billable, Notifiable;

    protected $guarded = [];
}