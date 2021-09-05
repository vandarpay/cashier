<?php


namespace Vandar\Cashier\Tests;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Vandar\Cashier\Vandar;
use Vandar\Cashier\VandarCashierServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp() : void
    {
        parent::setUp();
        $this->setUpDatabase();
        $this->withFactories(__DIR__ . '/../database/factories');
    }

    protected function getPackageProviders($app): array
    {
        return [VandarCashierServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('vandar.api_key', env('VANDAR_API_KEY'));
        $app['config']->set('vandar.mobile', env('VANDAR_MOBILE'));
        $app['config']->set('vandar.password', env('VANDAR_PASSWORD'));
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
    }

    /**
     * Runs migrations and defines a callback to revert them.
     *
     * Since we're not using the standard laravel format for publishing our migrations, we seem to have issues with
     * Laravel default migration tools. This workaround manually loads the migration classes and runs them on the database,
     * once done, it'll revert the migrations.
     */
    protected function setUpDatabase()
    {
        $active_migrations = Vandar::ACTIVE_MIGRATIONS;

        // Include and run all the migrations
        foreach($active_migrations as $migration){
            $file = Str::snake($migration);
            include_once(__DIR__  . "/../database/migrations/" . $file . ".php.stub");
            (new $migration)->up();
        }

        // Undo the migrations once the application is destroyed
        $this->beforeApplicationDestroyed(function() use ($active_migrations){
            foreach(array_reverse($active_migrations) as $migration){
                $file = Str::snake($migration);
                include_once(__DIR__  . "/../database/migrations/" . $file . ".php.stub");
                (new $migration)->down();
            }
        });
    }
}
