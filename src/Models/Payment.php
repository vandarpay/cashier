<?php

namespace Vandar\Cashier\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Vandar\Cashier\Client\CasingFormatter;
use Vandar\Cashier\Client\Client;
use Vandar\Cashier\Events\PaymentCreating;
use Vandar\Cashier\Vandar;

class Payment extends Model
{
    protected $table = 'vandar_payments';
    protected $guarded = ['id'];

    const STATUSES = [
        self::STATUS_SUCCEED,
        self::STATUS_FAILED
    ];
    const STATUS_SUCCEED = 'SUCCEED';
    const STATUS_FAILED = 'FAILED';

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
        'creating' => PaymentCreating::class
    ];

    public static function verifyFromRequest(Request $request) : bool
    {
        $status = $request->get('payment_status');
        if($status === 'OK')
        {
            $status = self::STATUS_SUCCEED;
        }

        return (new self)->where('token', $request->get('token'))->firstOrFail()->verify($request->get('payment_status'));
    }
    
    /**
     * Verify a given transactions
     *
     * @return bool
     */
    public function verify($request_status=null): bool
    {
        if ($request_status === 'OK')
        if($this->status !== 'INIT'){
            return $this->status == self::STATUS_SUCCEED;
        }

        if ($request_status === self::STATUS_FAILED){
         $this->update(['status' => self::STATUS_FAILED]);
         return false;
        }

        $endpoint = Vandar::url('IPG_API', 'verify');
        $params = ['api_key' => config('vandar.api_key'), 'token' => $this->token];

        $response = Client::request('post', $endpoint, $params, false);

        if ($response->getStatusCode() != 200) {
            $this->update([
                'errors' => json_encode($response->json()['errors']),
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

    public function getUrlAttribute(): string
    {
        return Vandar::url('IPG', $this->token);
    }
}
