<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords;

use Illuminate\Support\Collection;

class PropertiesKeyword extends Keyword
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
        $properties = collect($this->get())->mapWithKeys(function ($property) {
            return [$property->name() => $property->toArray()];
        })->all();

        return $schema->merge(compact('properties'));
    }
}
