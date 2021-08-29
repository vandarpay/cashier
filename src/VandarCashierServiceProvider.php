<?php

namespace Vandar\Cashier;

use Illuminate\Support\ServiceProvider;

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
        $config_path = realpath(__DIR__ . '/../config/vandar.php');
        
        
        // php artisan vendor:publish --provider="Vandar\\VandarCashier\\VandarCashierServiceProvider" --tag=vandar-config
        $this->publishes([
            $config_path => config_path('vandar.php'),
        ], 'vandar-config');
        
        
        // php artisan vendor:publish --provider="Vandar\\VandarCashier\\VandarCashierServiceProvider" --tag=vandar-migrations
        $this->loadMigrationsFrom(self::MIGRATIONS_PATH);
        $this->publishes([
            self::MIGRATIONS_PATH => database_path('migrations')
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


    protected function publishMigrations(array $publishables): void
    {
        // Generate a list of migrations that have not been published yet.
        $migrations = [];
        foreach($publishables as $publishable)
        {
            // Migration already exists, continuing
            if(class_exists($publishable)){
                continue;
            }
            $file = Str::snake($publishable) . '.php';
            $migrations[self::MIGRATIONS_PATH . $file . '.stub'] = database_path('migrations/'.date('Y_m_d_His', time())."_$file");
        }

        $this->publishes($migrations, 'migrations');
    }

}
