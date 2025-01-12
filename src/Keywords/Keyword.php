<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords;

use BasilLangevin\LaravelDataSchemas\Exceptions\KeywordValueCouldNotBeInferred;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use BasilLangevin\LaravelDataSchemas\Transformers\Reflector;
use Illuminate\Support\Collection;

abstract class Keyword
{
    /**
     * A custom name for the method that sets the keyword value.
     * The method name is automatically generated if not set.
     */
    public static string $method;

    /**
     * Get the name of the method that can be called to set the
     * value of the keyword on any schema type that uses it.
     */
    public static function method(): string
    {
        if (isset(static::$method)) {
            return static::$method;
        }

        return str(class_basename(static::class))
            ->beforeLast('Keyword')
            ->camel()
            ->value();
    }

    /**
     * Get the value of the keyword.
     */
    abstract public function get(): mixed;

    /**
     * Add the definition for the keyword to the given schema.
     */
    abstract public function apply(Collection $schema): Collection;

    /**
     * Infer the value of the keyword from the reflector, or throw
     * an exception if the schema should not have this keyword.
     *
     * @throws KeywordValueCouldNotBeInferred
     */
    abstract public static function parse(ReflectionHelper $reflector): mixed;
}
