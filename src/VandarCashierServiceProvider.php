<?php

namespace Vandar\VandarCashier;

use Illuminate\Support\ServiceProvider;

class VandarCashierServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Loading and Publishing migration files
         */
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->publishes([
            __DIR__ . '/../migrations/' => database_path('migrations')
        ], 'migrations');
    }
}


///  publish ->  php artisan vendor:publish --tag=Vandar\VandarCashier\VandarCashierServiceProvider   ///