<?php

namespace BasilLangevin\LaravelDataSchemas;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelDataSchemasServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-data-schemas')
            ->hasConfigFile();
    }
}
