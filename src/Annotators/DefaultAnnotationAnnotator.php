<?php

namespace BasilLangevin\LaravelDataSchemas\Annotators;

use BasilLangevin\LaravelDataSchemas\Annotators\Contracts\AnnotatesSchema;
use BasilLangevin\LaravelDataSchemas\Exceptions\KeywordNotSetException;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use ReflectionParameter;

class DefaultAnnotationAnnotator implements AnnotatesSchema
{
    public static function annotateSchema(Schema $schema, EntityWrapper $entity): Schema
    {
        if (! $entity instanceof PropertyWrapper) {
            return $schema;
        }

        try {
            return $schema->default(self::getDefaultValue($entity));
        } catch (KeywordNotSetException $e) {
            return $schema;
        }
    }

    protected static function getDefaultValue(PropertyWrapper $property): mixed
    {
        if ($property->hasDefaultValue()) {
            return $property->getDefaultValue();
        }

        $parameter = self::getConstructorParameter($property);

        if (! $parameter || ! $parameter->isOptional()) {
            throw new KeywordNotSetException;
        }

        return $parameter->getDefaultValue() ?? null;
    }

    /**
     * Get the constructor parameter that matches the property name.
     */
    protected static function getConstructorParameter(
        PropertyWrapper $property,
    ): ?ReflectionParameter {
        $class = $property->getClass();

        if (! $class->hasConstructor()) {
            return null;
        }

        return collect($class->getConstructor()->getParameters())
            ->first(function (ReflectionParameter $parameter) use ($property) {
                return $parameter->getName() === $property->getName();
            }) ?? null;
    }
}
