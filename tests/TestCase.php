<?php


namespace Vandar\Cashier\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Vandar\Cashier\Vandar;
use Vandar\Cashier\VandarCashierServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;
    public function setUp() : void
    {
        parent::setUp();
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
        $app['config']->set('vandar.business_slug', env('VANDAR_BUSINESS_SLUG'));
        $app['config']->set('vandar.callback_url', env('VANDAR_CALLBACK_URL', 'http://127.0.0.1:8000/callback'));
        $app['config']->set('vandar.notify_url', env('VANDAR_CALLBACK_URL', 'http://127.0.0.1:8000/notify'));
        $app['config']->set('vandar.mandate_callback_url', env('VANDAR_MANDATE_CALLBACK_URL', 'http://127.0.0.1:8000/mandate-callback'));
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
}
