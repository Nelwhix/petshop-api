<?php

namespace Tests;

use Nelwhix\CurrencyExchange\CurrencyExchangeServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders()
    {
        return [
          CurrencyExchangeServiceProvider::class,
        ];
    }
}
