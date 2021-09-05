<?php

namespace Vandar\Cashier\Models;

use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Vandar\Cashier\Events\WithdrawalCreating;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
