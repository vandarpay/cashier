<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarSubscriptionController;


class VandarSubscription
{
    /**
     * Store new subscription
     *
     * @param array $params
     * 
     */
    public static function store($params)
    {
        VandarSubscriptionController::store($params);
    }


    /**
     * Show the list of subscriptions
     *
     * @return array $subscriptions
     */
    public static function list()
    {
        VandarSubscriptionController::list();
    }



    /**
     * Get the details of subscription by ID
     *
     * @param string $subscription_code
     * 
     */
    public static function show($subscription_code)
    {
        VandarSubscriptionController::show($subscription_code);
    }




    /**
     * Revoke the subscription
     *
     * @param string $subscription_code
     * 
     */
    public static function revoke($subscription_code)
    {
        VandarSubscriptionController::revoke($subscription_code);
    }

    /**
     * check and verify the subscription
     */
    public static function verifySubscription()
    {
        VandarSubscriptionController::verifySubscription();
    }
}
