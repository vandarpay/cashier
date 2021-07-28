<?php

namespace Vandar\VandarCashier;

use Illuminate\Support\ServiceProvider;

class VandarCashierServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $migrations_path =  realpath(__DIR__ . '/../migrations/');

        $this->loadMigrationsFrom($migrations_path);
        $this->publishes([
            $migrations_path => database_path('migrations')
        ], 'migrations');
    }



    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
