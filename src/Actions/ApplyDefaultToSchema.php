<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use ReflectionParameter;

class ApplyDefaultToSchema
{
    use Runnable;

    public function handle(Schema $schema, PropertyWrapper $property): Schema
    {
        if ($property->hasDefaultValue()) {
            return $schema->default($property->getDefaultValue());
        }

        $parameter = self::getConstructorParameter($property);

        if (! $parameter || ! $parameter->isOptional()) {
            return $schema;
        }

        return $schema->default($parameter->getDefaultValue() ?? null);
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
