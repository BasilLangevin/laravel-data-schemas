<?php

namespace BasilLangevin\LaravelDataSchemas;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use BasilLangevin\LaravelDataSchemas\Commands\LaravelDataSchemasCommand;

class LaravelDataSchemasServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-data-schemas')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_data_schemas_table')
            ->hasCommand(LaravelDataSchemasCommand::class);
    }
}
