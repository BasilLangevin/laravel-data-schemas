<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use Spatie\LaravelData\Attributes\Validation\Present;
use Spatie\LaravelData\Attributes\Validation\Required;

class TransformDataClassToSchema
{
    use Runnable;

    public function handle(ClassWrapper $class)
    {
        $properties = $this->getProperties($class);
        $required = $this->getRequired($class);

        return ObjectSchema::make($class->getName())
            ->type('object')
            ->pipe(fn (Schema $schema) => ApplyAnnotationsToSchema::run($schema, $class))
            ->pipe(fn (Schema $schema) => ApplyRuleConfigurationsToSchema::run($schema, $class))
            ->when(count($properties), fn (Schema $schema) => $schema->properties($properties))
            ->when(count($required), fn (Schema $schema) => $schema->required($required));
    }

    /**
     * Get the properties of the class, transforming each into a Schema object.
     */
    protected function getProperties(ClassWrapper $class): array
    {
        return $class->properties()
            ->map(function (PropertyWrapper $property) {
                return TransformPropertyToSchema::run($property);
            })
            ->toArray();
    }

    /**
     * Get the required properties of the class.
     */
    protected function getRequired(ClassWrapper $class): array
    {
        return collect([
            ...$this->getNotNullProperties($class),
            ...$this->getPresentProperties($class),
            ...$this->getRequiredProperties($class),
        ])
            ->unique()
            ->toArray();
    }

    /**
     * Get the class properties that are not nullable.
     */
    protected function getNotNullProperties(ClassWrapper $class): array
    {
        return $class->properties()
            ->filter(function (PropertyWrapper $property) {
                $type = $property->getType();

                return $type !== null && ! $type->allowsNull();
            })
            ->map->getName()
            ->toArray();
    }

    /**
     * Get the class properties that have the Present attribute.
     */
    protected function getPresentProperties(ClassWrapper $class): array
    {
        return $class->properties()
            ->filter(function (PropertyWrapper $property) {
                return $property->hasAttribute(Present::class);
            })
            ->map->getName()
            ->toArray();
    }

    /**
     * Get the class properties that have the Required attribute.
     */
    protected function getRequiredProperties(ClassWrapper $class): array
    {
        return $class->properties()
            ->filter(function (PropertyWrapper $property) {
                return $property->hasAttribute(Required::class);
            })
            ->map->getName()
            ->toArray();
    }
}
