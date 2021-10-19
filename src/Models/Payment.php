<?php

namespace Vandar\Cashier\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Vandar\Cashier\Client\CasingFormatter;
use Vandar\Cashier\Client\Client;
use Vandar\Cashier\Events\PaymentCreating;
use Vandar\Cashier\Exceptions\ResponseException;
use Vandar\Cashier\Vandar;

/**
 * Payments made through the IPG method
 *
 * @property int id
 * @property string token the token used to make the payment
 * @property mixed amount
 * @property mixed real_amount
 * @property string wage amount of the wage taken
 * @property string status current status for this payment, INIT for initialized but not paid yet, SUCCEED and FAILED for transaction results
 * @property string mobile_number
 * @property string trans_id
 * @property string ref_number
 * @property string tracking_code
 * @property string factor_number
 * @property string description
 * @property string valid_card_number
 * @property string card_number
 * @property string cid
 * @property mixed payment_date
 * @property string messages
 * @property array errors
 *
 * @property string url custom attribute returning the url for payment gateway
 *
 * @mixin Builder
 */
class Payment extends Model
{
    const STATUSES = [
        self::STATUS_SUCCEED,
        self::STATUS_FAILED
    ];
    const STATUS_SUCCEED = 'SUCCEED';
    const STATUS_FAILED = 'FAILED';
    protected $table = 'vandar_payments';
    protected $guarded = ['id'];
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'creating' => PaymentCreating::class
    ];

    public static function verifyFromRequest(Request $request): bool
    {
        $status = $request->get('payment_status');
        if ($status === 'OK') {
            $status = self::STATUS_SUCCEED;
        }

        return (new self)->where('token', $request->get('token'))->firstOrFail()->verify($request->get('payment_status'));
    }

    /**
     * Verify a given transactions
     *
     * @return bool
     */
    public function verify($request_status = null): bool
    {
        if ($request_status === 'OK')
            if ($this->status !== 'INIT') {
                return $this->status == self::STATUS_SUCCEED;
            }

        if ($request_status === self::STATUS_FAILED) {
            $this->update(['status' => self::STATUS_FAILED]);
            return false;
        }

        $endpoint = Vandar::url('IPG_API', 'verify');
        $params = ['api_key' => config('vandar.api_key'), 'token' => $this->token];

        try {
            $response = Client::request('post', $endpoint, $params, false);
        } catch (ResponseException $exception) {
            $this->update([
                'errors' => json_encode($exception->getResponse()->json()['errors']),
                'status' => 'FAILED'
            ]);
            return false;
        }

        # prepare response for making compatible with DB
        $response = CasingFormatter::convertKeysToSnake($response->json());
        $response = CasingFormatter::mobileKeyFormat($response);

        $response['status'] = 'SUCCEED';
        $this->update($response);

        return true;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return Vandar::url('IPG', $this->token);
    }
}
