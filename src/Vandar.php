<?php

namespace Vandar\Cashier;

use Vandar\Cashier\Controllers\VandarIPGController;
use Vandar\Cashier\Controllers\VandarBillsController;
use Vandar\Cashier\Controllers\VandarMandateController;
use Vandar\Cashier\Controllers\VandarSettlementController;
use Vandar\Cashier\Controllers\VandarWithdrawalController;

class Vandar
{
    const VERSION = '1.0';

    const ACTIVE_MIGRATIONS = [
        'CreateVandarMandatesTable',
        'CreateVandarPaymentsTable',
        'CreateVandarSettlementsTable',
        'CreateVandarWithdrawalsTable',
    ];

    const API_BASE_URL = 'https://api.vandar.io/';

    const API_AUTH_VERSION = 3;

    use \Vandar\Cashier\Utilities\CheckStatus;


    /**
     * Billing
     */
    public static function Bills()
    {
        return new VandarBillsController;
    }

    /**
     * IPG
     */
    public static function IPG()
    {
        return new VandarIPGController;
    }


    /**
     * Mandate
     */
    public static function Mandate()
    {
        return new VandarMandateController;
    }


    /**
     * Settlement
     */
    public static function Settlement()
    {
        return new VandarSettlementController;
    }


    /**
     * Withdrawal
     */
    public static function Withdrawal()
    {
        return new VandarWithdrawalController;
    }




    /**
     * Check Status (Payment, Mandate)
     */
    public static function CheckStatus($request = null)
    {
        return self::checkerIndex($request ?? (\Request::query()));
    }
}
