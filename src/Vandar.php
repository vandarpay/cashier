<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarIPGController;
use Vandar\VandarCashier\Controllers\VandarAuthController;
use Vandar\VandarCashier\Controllers\VandarBillsController;
use Vandar\VandarCashier\Controllers\VandarMandateController;
use Vandar\VandarCashier\Controllers\VandarBusinessController;
use Vandar\VandarCashier\Controllers\VandarSettlementController;
use Vandar\VandarCashier\Controllers\VandarWithdrawalController;

class Vandar
{
    /**
     * Authentication
     */
    public static function Auth()
    {
        return new VandarAuthController;
    }


    /**
     * Billing
     */
    public static function Bills()
    {
        return new VandarBillsController;
    }


    /**
     * Business
     */
    public static function Business()
    {
        return new VandarBusinessController;
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



    
    
    use \Vandar\VandarCashier\Traits\VandarVerify;
    public static function Verify()
    {
        return self::verifyTrait((\Request::query()));
    }
}
