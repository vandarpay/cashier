<?php


namespace Vandar\Cashier\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Vandar\Cashier\VandarCashierServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [VandarCashierServiceProvider::class];
    }
}
