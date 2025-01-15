<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\String;

use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class MinLengthKeyword extends Keyword implements HandlesMultipleInstances
{
    public function __construct(protected int $value) {}

    /**
     * Get the value of the keyword.
     */
    public function get(): int
    {
        return $this->value;
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge(['minLength' => $this->value]);
    }

    /**
     * Apply multiple instances of the keyword to the schema.
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        return $schema->merge(['minLength' => $instances->max->get()]);
    }
}
