<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Generic;

use BasilLangevin\LaravelDataSchemas\Enums\Format;
use BasilLangevin\LaravelDataSchemas\Exceptions\SchemaConfigurationException;
use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class FormatKeyword extends Keyword implements HandlesMultipleInstances
{
    public function __construct(protected string|Format $value) {}

    /**
     * Get the value of the keyword.
     */
    public function get(): string|Format
    {
        return $this->value;
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge([
            'format' => $this->value instanceof Format
                ? $this->value->value
                : $this->value,
        ]);
    }

    /**
     * Apply the format keyword to a schema when multiple instances are applied.
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        if ($instances->map->get()->unique()->count() > 1) {
            throw new SchemaConfigurationException('A schema cannot have more than one format.');
        }

        return $instances->first()->apply($schema);
    }
}
