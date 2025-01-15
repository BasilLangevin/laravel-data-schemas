<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Object;

use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class PropertiesKeyword extends Keyword implements HandlesMultipleInstances
{
    public function __construct(protected array $value) {}

    /**
     * Get the value of the keyword.
     */
    public function get(): mixed
    {
        return $this->value;
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        $properties = self::resolveProperties(
            collect($this->get())
        );

        return $schema->merge(compact('properties'));
    }

    /**
     * Apply multiple instances of the keyword to the schema.
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        $properties = self::resolveProperties(
            $instances->flatMap->get()->unique(fn ($property, $name) => $name)
        );

        return $schema->merge(compact('properties'));
    }

    /**
     * Resolve the properties from the given collection.
     */
    protected static function resolveProperties(Collection $properties): array
    {
        return $properties->mapWithKeys(function ($property) {
            return [$property->name() => $property->toArray()];
        })->all();
    }
}
