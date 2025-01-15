<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Contracts;

use Illuminate\Support\Collection;

interface HandlesMultipleInstances
{
    public static function applyMultiple(Collection $schema, Collection $instances): Collection;
}
