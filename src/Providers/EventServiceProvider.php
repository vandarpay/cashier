<?php

namespace Vandar\Cashier\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Vandar\Cashier\Events\MandateCreating;
use Vandar\Cashier\Events\PaymentCreating;
use Vandar\Cashier\Events\SettlementCreating;
use Vandar\Cashier\Events\WithdrawalCreating;
use Vandar\Cashier\Listeners\SendMandateCreateRequest;
use Vandar\Cashier\Listeners\SendPaymentCreateRequest;
use Vandar\Cashier\Listeners\SendSettlementCreateRequest;
use Vandar\Cashier\Listeners\SendWithdrawalCreateRequest;

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
            SendMandateCreateRequest::class
        ],
        WithdrawalCreating::class => [
            SendWithdrawalCreateRequest::class
        ],
        SettlementCreating::class => [
          SendSettlementCreateRequest::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
