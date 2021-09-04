<?php

namespace Vandar\Cashier\Providers;

use Vandar\Cashier\Events\MandateCreating;
use Vandar\Cashier\Events\MandateRedirect;
use Vandar\Cashier\Events\PaymentCreated;
use Vandar\Cashier\Events\PaymentRedirect;
use Vandar\Cashier\Events\WithdrawalCreating;
use Vandar\Cashier\Events\WithdrawalResponse;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        PaymentCreated::class => [
            PaymentRedirect::class
        ],
        MandateCreating::class => [
            MandateRedirect::class
        ],
        WithdrawalCreating::class => [
            WithdrawalResponse::class
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
