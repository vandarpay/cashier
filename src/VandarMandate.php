<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarMandateController;


class VandarMandate
{
    /**
     * Store new subscription
     *
     * @param array $params
     * 
     */
    public static function store($params)
    {
        VandarMandateController::store($params);
    }


    /**
     * Show the list of subscriptions
     *
     * @return array 
     */
    public static function list()
    {
        VandarMandateController::list();
    }



    /**
     * Get the details of subscription by ID
     *
     * @param string $subscription_code
     * 
     * @return 
     */
    public static function show($subscription_code)
    {
        VandarMandateController::show($subscription_code);
    }




    /**
     * Revoke the subscription
     *
     * @param string $subscription_code
     * 
     */
    public static function revoke($subscription_code)
    {
        VandarMandateController::revoke($subscription_code);
    }

    /**
     * check and verify the subscription
     */
    public static function verifyMandate()
    {
        VandarMandateController::verifyMandate();
    }

    public function __call($name, $arguments)
    {
        dd($name . " " . $arguments);
    }

}
