<?php

namespace BasilLangevin\LaravelDataSchemas\Tests;

use BasilLangevin\LaravelDataSchemas\LaravelDataSchemasServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelData\LaravelDataServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelDataSchemasServiceProvider::class,
            LaravelDataServiceProvider::class,
        ];
    }
}
