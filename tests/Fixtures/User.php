<?php

namespace Vandar\Cashier\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Model;
use Illuminate\Notifications\Notifiable;
use Vandar\Cashier\Traits\Billable;

/**
 * @property int id
 */
class User extends Model
{
    use Billable, Notifiable;

    protected $guarded = [];
}