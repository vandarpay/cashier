<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarMandateController;


class VandarMandate
{
    /**
     * Show the list of subscriptions
     *
     * @return array 
     */
    public static function list()
    {
        return VandarMandateController::list();
    }


    

    /**
     * Store new subscription
     *
     * @param array $params
     * 
     */
    public static function store($params)
    {
        return VandarMandateController::store($params);
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
        return VandarMandateController::show($subscription_code);
    }




    /**
     * Revoke the subscription
     *
     * @param string $subscription_code
     * 
     */
    public static function revoke($subscription_code)
    {
        return VandarMandateController::revoke($subscription_code);
    }

}
