<?php

namespace Vandar\Cashier;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Vandar\Cashier\Providers\EventServiceProvider;

class VandarCashierServiceProvider extends ServiceProvider
{
    const MIGRATIONS_PATH = __DIR__ . '/../database/migrations/';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $config_path = realpath(__DIR__ . '/../config/vandar.php');


        // php artisan vendor:publish --provider="Vandar\\Cashier\\VandarCashierServiceProvider" --tag=vandar-config
        $this->publishes([
            $config_path => config_path('vandar.php'),
        ], 'vandar-config');


        // php artisan vendor:publish --provider="Vandar\\Cashier\\VandarCashierServiceProvider" --tag=vandar-migrations
        // $this->publishMigrations(Vandar::ACTIVE_MIGRATIONS);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'prefix' => config('vandar.path'),
            'namespace' => 'Vandar\Cashier\Http\Controllers',
            'as' => 'vandar.',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
    }

}
