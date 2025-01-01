<?php

namespace BasilLangevin\LaravelDataSchemas\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BasilLangevin\LaravelDataSchemas\LaravelDataSchemas
 */
class LaravelDataSchemas extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \BasilLangevin\LaravelDataSchemas\LaravelDataSchemas::class;
    }
}
