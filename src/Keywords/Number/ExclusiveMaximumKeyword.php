<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Number;

use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class ExclusiveMaximumKeyword extends Keyword implements HandlesMultipleInstances
{
    public function __construct(protected int|float $value) {}

    /**
     * Get the value of the keyword.
     */
    public function get(): int|float
    {
        return $this->value;
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge(['exclusiveMaximum' => $this->value]);
    }

    /**
     * Apply multiple instances of the keyword to the given schema.
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        return $schema->merge(['exclusiveMaximum' => $instances->min->get()]);
    }
}
