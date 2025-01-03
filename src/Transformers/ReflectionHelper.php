<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers;

use BadMethodCallException;
use Illuminate\Support\Collection;
use ReflectionProperty;
use Reflector;

class ReflectionHelper
{
    public function __construct(
        protected Reflector $reflector,
    ) {}

    /**
     * Call a method on the property.
     */
    public function __call(string $name, array $arguments)
    {
        if (! method_exists($this->reflector, $name)) {
            throw new BadMethodCallException('The reflector does not have a '.$name.' method.');
        }

        return $this->reflector->$name(...$arguments);
    }

    /**
     * Get the class name of the reflector.
     */
    public function getReflectorClassName(): string
    {
        return get_class($this->reflector);
    }

    /**
     * Check if the property has a specific attribute.
     */
    public function hasAttribute(string $attribute): bool
    {
        return $this->getAttributes($attribute) !== [];
    }

    /**
     * Get an attribute from the property.
     */
    public function getAttribute(string $attribute): ?object
    {
        if (! $this->hasAttribute($attribute)) {
            return null;
        }

        return $this->getAttributes($attribute)[0]
            ->newInstance();
    }

    /**
     * Get the properties of the reflector as a collection.
     */
    public function properties(): Collection
    {
        return collect(
            $this->getProperties(ReflectionProperty::IS_PUBLIC)
        );
    }
}
