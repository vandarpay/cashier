<?php

namespace Vandar\Cashier\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Vandar\Cashier\Events\PaymentCreated;
use Vandar\Cashier\Events\PaymentRedirect;

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
