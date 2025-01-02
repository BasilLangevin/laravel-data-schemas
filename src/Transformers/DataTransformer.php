<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers;

use BasilLangevin\LaravelDataSchemas\Transformers\Properties\PropertyTransformer;
use BasilLangevin\LaravelDataSchemas\Types\ObjectSchema;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionProperty;

class DataTransformer
{
    public function __construct(protected string $dataClass) {}

    /**
     * Transform a Spatie Data class into a Schema object.
     */
    public static function transform(string $dataClass): ObjectSchema
    {
        return (new self($dataClass))->build();
    }

    /**
     * Build the Schema object.
     */
    protected function build(): ObjectSchema
    {
        return ObjectSchema::make($this->getName())
            ->properties($this->transformProperties())
            ->required($this->getRequiredProperties());
    }

    /**
     * Get the ReflectionClass instance for the data class.
     */
    protected function reflection(): ReflectionClass
    {
        return new ReflectionClass($this->dataClass);
    }

    /**
     * Get the name of the data class.
     */
    protected function getName(): string
    {
        return $this->reflection()->getShortName();
    }

    /**
     * Get the data class' public properties.
     */
    protected function getProperties(): Collection
    {
        return collect($this->reflection()->getProperties(ReflectionProperty::IS_PUBLIC));
    }

    /**
     * Transform the properties into an array of Schema objects.
     */
    protected function transformProperties(): array
    {
        return $this->getProperties()
            ->map(function (ReflectionProperty $property) {
                return PropertyTransformer::transform($property);
            })->toArray();
    }

    /**
     * Get the required properties.
     */
    protected function getRequiredProperties(): array
    {
        return $this->getProperties()
            ->filter(function (ReflectionProperty $property) {
                $type = $property->getType();

                return $type !== null && ! $type->allowsNull();
            })
            ->map->getName()
            ->toArray();
    }
}
