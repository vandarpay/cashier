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
        $migrations_path =  __DIR__ . '/../migrations/';
        
        
        $this->loadMigrationsFrom($migrations_path);

        // php artisan vendor:publish --provider="Vandar\\VandarCashier\\VandarCashierServiceProvider" --tag=migrations
        $this->publishes([
            $migrations_path => database_path('migrations')
        ], 'migrations');
    }
}


