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

    // URL Generation Functionality
    protected const BASE_URL = 'https://api.vandar.io/';
    protected const IPG_BASE_URL = 'https://ipg.vandar.io/';
    protected const API_VERSIONS = [
        'AUTH' => '3',
        'TRANSACTION' => '2',
        'SETTLEMENT' => '2.1',
        'IPG' => '3',
        'MANDATE' => '2',
        'WITHDRAWAL' => '2',
    ];

    /**
     * @param string $api can be AUTH, TRANSACTION, SETTLEMENT, IPG, MANDATE, or WITHDRAWAL
     * @param string $additional Additional portion to add to the url
     * @return string full url for the selected endpoint
     */
    public static function url(string $api, string $additional='')
    {
        // TODO allow dynamic version assignment
        $api = strtoupper($api);
        // TODO add custom exception for when given API is missing.
        $base_url = in_array($api, ['IPG', 'IPG_API']) ? self::IPG_BASE_URL : self::BASE_URL;

        // TODO refactor this
        if($api == 'IPG_API'){
            $base_url .= 'api/';
            $api = 'IPG';
        }

        return $base_url . 'v' . self::API_VERSIONS[$api] . '/' . $additional;
    }

    use \Vandar\Cashier\Utilities\CheckStatus;
    
    /**
     * Check Status (Payment, Mandate)
     */
    public static function CheckStatus($request = null)
    {
        return self::checkerIndex($request ?? (\Request::query()));
    }
}
