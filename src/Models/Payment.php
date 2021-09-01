<?php

namespace Vandar\Cashier\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Vandar\Cashier\Database\Factories\PaymentFactory;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'vandar_payments';
    protected $guarded =['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function newFactory()
    {
        return new PaymentFactory;
    }
}
