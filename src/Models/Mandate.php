<?php

namespace Vandar\Cashier\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Vandar\Cashier\Events\MandateCreating;

/**
 * Vandar Mandates
 *
 * @property string token
 * @property string authorization_id
 * @property string name
 * @property string email
 * @property string mobile_number
 * @property integer count the maximum number of withdrawals possible for this mandate
 * @property mixed limit the maximum transaction amount per withdrawal
 * @property mixed expiration_date the date when given mandate expires
 * @property string bank_code the bank code authorizing this mandate
 * @property string status the status of current mandate
 * @property bool is_active whether current mandate is active
 * @property array errors
 */
class Mandate extends Model
{
    protected $table = 'vandar_mandates';
    protected $guarded = ['id'];

    const STATUSES = [
        self::STATUS_INIT,
        self::STATUS_SUCCEED,
        self::STATUS_FAILED,
        self::STATUS_FAILED_TO_ACCESS_BANK
    ];
    const STATUS_INIT = 'INIT';
    const STATUS_SUCCEED = 'SUCCEED';
    const STATUS_FAILED = 'FAILED';
    const STATUS_FAILED_TO_ACCESS_BANK = 'FAILED_TO_ACCESS_BANK';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }


     /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'creating' => MandateCreating::class
    ];
}
