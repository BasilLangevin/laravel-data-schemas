<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords;

use BasilLangevin\LaravelDataSchemas\Exception\KeywordValueCouldNotBeInferred;
use BasilLangevin\LaravelDataSchemas\Transformers\PropertyTransformer;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use Illuminate\Support\Collection;
use ReflectionProperty;

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

    /**
     * Infer the value of the keyword from the object, or return
     * null if the object schema should not have this keyword.
     */
    public static function parse(ReflectionHelper $reflector): array
    {
        $properties = $reflector->properties()
            ->map(function (ReflectionProperty $property) {
                return PropertyTransformer::transform($property);
            });

        if ($properties->isEmpty()) {
            throw new KeywordValueCouldNotBeInferred;
        }

        return $properties->toArray();
    }
}
