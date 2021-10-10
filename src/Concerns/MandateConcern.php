<?php

namespace Vandar\Cashier\Concerns;

use Vandar\Cashier\Models\Mandate;
use Vandar\Cashier\Vandar;

trait MandateConcern
{
    public function hasValidMandate()
    {
        if ($this->mandates()->where('expiration_date', '>', date('Y-m-d'))->exists())
            return true;

        return false;
    }

    public function authorizeMandate($bank_code, $mobile, $count, $limit, $expiration_date = null, $wage_type = null)
    {
        /**
         * @var Mandate
         */
        $mandate = $this->mandates()->create(['bank_code' => $bank_code, 'mobile_number' => $mobile, 'count' => $count,
            'limit' => $limit, 'expiration_date' => $expiration_date, 'wage_type' => $wage_type]);

        return Vandar::url('MANDATE', $mandate->token);
    }
}