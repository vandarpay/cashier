<?php

namespace Vandar\Cashier\Models;

use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Vandar\Cashier\Events\WithdrawalCreating;

/**
 * Withdrawal Model
 *
 * @property string authorization_id the uuid for the mandate in Vandar APIs
 * @property string withdrawal_id the uuid for this withdrawal in Vandar APIs
 *
 * @property User user
 */
class Withdrawal extends Model
{
    protected $table = 'vandar_withdrawals';
    protected $guarded = ['id'];

    const STATUSES = [
        self::STATUS_INIT,
        self::STATUS_PENDING,
        self::STATUS_DONE,
        self::STATUS_CANCELED,
        self::STATUS_FAILED
    ];
    const STATUS_INIT = 'INIT';
    const STATUS_PENDING = 'PENDING';
    const STATUS_DONE = 'DONE';
    const STATUS_CANCELED = 'CANCELED';
    const STATUS_FAILED = 'FAILED';

    protected $dispatchesEvents = [
        'creating' => WithdrawalCreating::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(User::class, Mandate::class, 'authorization_id', 'id', 'authorization_id', 'user_id');
    }

    public function mandate()
    {
        return $this->belongsTo(Mandate::class, 'authorization_id', 'authorization_id');
    }
}
