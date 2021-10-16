<?php

namespace Vandar\Cashier\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Settlement Model
 *
 * @property string settlement_id the model's id in Vandar APIs
 * @property int amount the requested amount for settlement
 * @property int amount_toman the requested amount for settlement in Toman
 * @property int wage_toman the amount of wage taken from the settlement
 * @property string iban the iban number for which the settlement is requested
 * @property int iban_id the iban id that Vandar has dedicated for the selected IBAN
 * @property string track_id the internally set track id for settlement to avoid double spending
 * @property string payment_number
 * @property string transaction_id
 * @property string status
 * @property int wallet
 * @property bool is_instant
 * @property mixed settlement_date
 * @property mixed settlement_time
 * @property string settlement_date_jalali
 * @property string settlement_done_time_prediction
 * @property array errors
 */
class Settlement extends Model
{
    protected $table = 'vandar_settlements';
    protected $guarded = ['id'];
}
