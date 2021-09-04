<?php

namespace Vandar\Cashier\Providers;

use Vandar\Cashier\Events\MandateCreating;
use Vandar\Cashier\Events\PaymentCreating;
use Vandar\Cashier\Events\WithdrawalCreating;
use Vandar\Cashier\Listeners\SendMandateRequest;
use Vandar\Cashier\Listeners\SendPaymentCreateRequest;
use Vandar\Cashier\Listeners\SendWithdrawalCreateRequest;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        PaymentCreating::class => [
            SendPaymentCreateRequest::class
        ],
        MandateCreating::class => [
            SendMandateRequest::class
        ],
        WithdrawalCreating::class => [
            SendWithdrawalCreateRequest::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
