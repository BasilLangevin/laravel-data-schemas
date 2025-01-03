<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords;

use BasilLangevin\LaravelDataSchemas\Exception\KeywordValueCouldNotBeInferred;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use Illuminate\Support\Collection;
use ReflectionParameter;

class DefaultKeyword extends Keyword
{
    public function __construct(protected mixed $value) {}

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
        return $schema->merge([
            'default' => $this->get(),
        ]);
    }

    /**
     * Infer the value of the keyword from the property, or return
     * null if the property schema should not have this keyword.
     */
    public static function parse(ReflectionHelper $reflector): mixed
    {
        if ($reflector->hasDefaultValue()) {
            return $reflector->getDefaultValue();
        }

        $parameter = self::getConstructorParameter($reflector);

        if (! $parameter || ! $parameter->isOptional()) {
            throw new KeywordValueCouldNotBeInferred;
        }

        return $parameter->getDefaultValue() ?? null;
    }

    /**
     * Get the constructor parameter for the given reflector.
     */
    protected static function getConstructorParameter(
        ReflectionHelper $reflector,
    ): ?ReflectionParameter {
        $class = $reflector->getDeclaringClass();

        if (! $class->hasMethod('__construct')) {
            return null;
        }

        $constructor = $class->getMethod('__construct');

        return collect($constructor->getParameters())
            ->first(function (ReflectionParameter $parameter) use ($reflector) {
                return $parameter->getName() === $reflector->getName();
            });
    }
}
