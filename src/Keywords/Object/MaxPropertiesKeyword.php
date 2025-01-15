<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Object;

use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class MaxPropertiesKeyword extends Keyword implements HandlesMultipleInstances
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
        return $schema->merge(['maxProperties' => $this->value]);
    }

    /**
     * Apply the keyword to the schema for multiple values.
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        return $schema->merge(['maxProperties' => $instances->min->get()]);
    }
}
