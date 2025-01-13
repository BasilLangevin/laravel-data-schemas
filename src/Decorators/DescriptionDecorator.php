<?php

namespace BasilLangevin\LaravelDataSchemas\Decorators;

use BasilLangevin\LaravelDataSchemas\Attributes\Description;
use BasilLangevin\LaravelDataSchemas\Decorators\Contracts\DecoratesSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class DescriptionDecorator implements DecoratesSchema
{
    public static function decorateSchema(Schema $schema, EntityWrapper $entity): Schema
    {
        if ($attribute = $entity->getAttribute(Description::class)) {
            return $schema->description($attribute->getValue());
        }

        $description = match (true) {
            $entity instanceof PropertyWrapper => static::getPropertyDescription($entity),
            $entity instanceof ClassWrapper => static::getClassDescription($entity),
        };

        if (! $description) {
            return $schema;
        }

        return $schema->description($description);
    }

    /**
     * Get the description for a property.
     */
    protected static function getPropertyDescription(PropertyWrapper $property): ?string
    {
        $propertyDoc = $property->getDocBlock();
        $constructorDoc = $property->getClass()->getConstructorDocBlock();
        $classDoc = $property->getClass()->getDocBlock();

        $name = $property->getName();

        return $propertyDoc?->getParamDescription($name)
            ?? $propertyDoc?->getVarDescription()
            ?? $propertyDoc?->getDescription()
            ?? $propertyDoc?->getSummary()
            ?? $constructorDoc?->getParamDescription($name)
            ?? $classDoc?->getVarDescription($name)
            ?? null;
    }

    /**
     * Get the description for a class.
     */
    protected static function getClassDescription(ClassWrapper $class): ?string
    {
        $docBlock = $class->getDocBlock();

        return $docBlock?->getDescription()
            ?? $docBlock?->getSummary()
            ?? null;
    }
}
