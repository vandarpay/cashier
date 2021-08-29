<?php

namespace Vandar\VandarCashier\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
