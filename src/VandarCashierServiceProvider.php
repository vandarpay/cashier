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
        $config_path = realpath(__DIR__ . '/../config/vandar.php');
        
        $migrations_path =  realpath(__DIR__ . '/../migrations/');
        
        
        // php artisan vendor:publish --provider="Vandar\\VandarCashier\\VandarCashierServiceProvider" --tag=vandar-config
        $this->publishes([
            $config_path => config_path('vandar.php'),
        ], 'vandar-config');
        
        
        // php artisan vendor:publish --provider="Vandar\\VandarCashier\\VandarCashierServiceProvider" --tag=vandar-migrations
        $this->loadMigrationsFrom($migrations_path);
        $this->publishes([
            $migrations_path => database_path('migrations')
        ], 'vandar-migrations');
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
