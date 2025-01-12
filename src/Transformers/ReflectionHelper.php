<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers;

use BadMethodCallException;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionProperty;
use Reflector;

class ReflectionHelper
{
    public function __construct(
        protected Reflector $reflector,
    ) {}

    /**
     * Determine if the reflected entity is a class.
     */
    public function isClass(): bool
    {
        return $this->reflector instanceof ReflectionClass;
    }

    /**
     * Determine if the reflected entity is a property.
     */
    public function isProperty(): bool
    {
        return $this->reflector instanceof ReflectionProperty;
    }

    /**
     * Determine if the reflected entity is a string.
     */
    public function isString(): bool
    {
        if (! $this->isProperty()) {
            return false;
        }

        return $this->getType()->getName() === 'string';
    }

    /**
     * Determine if the reflected entity is an integer.
     */
    public function isInteger(): bool
    {
        if (! $this->isProperty()) {
            return false;
        }

        return $this->getType()->getName() === 'int';
    }

    /**
     * Determine if the reflected entity is a boolean.
     */
    public function isBoolean(): bool
    {
        if (! $this->isProperty()) {
            return false;
        }

        return $this->getType()->getName() === 'bool';
    }

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
     * Get a property of the reflector.
     */
    public function __get(string $name)
    {
        return $this->reflector->$name;
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
    public function getAttribute(string $attribute): object|array|null
    {
        if (! $this->hasAttribute($attribute)) {
            return null;
        }

        if (count($this->getAttributes($attribute)) > 1) {
            return collect($this->getAttributes($attribute))->map->newInstance();
        }

        return $this->getAttributes($attribute)[0]
            ->newInstance();
    }

    /**
     * Get the declaring class of the property as a ReflectionHelper.
     */
    public function getClass(): ReflectionHelper
    {
        if (! $this->isProperty()) {
            throw new \Exception('The reflector is not a property.');
        }

        return new ReflectionHelper($this->getDeclaringClass());
    }

    /**
     * Get the properties of the reflector as a collection.
     */
    public function properties(): Collection
    {
        return collect($this->getProperties(ReflectionProperty::IS_PUBLIC))
            ->map(function (ReflectionProperty $property) {
                return new ReflectionHelper($property);
            });
    }

    /**
     * Get the siblings of the property as a collection.
     */
    public function siblings(): Collection
    {
        return collect($this->getClass()->properties())
            ->filter(function (ReflectionHelper $property) {
                return $property->getName() !== $this->getName();
            })
            ->values();
    }

    /**
     * Get the sibling names of the property as a collection.
     */
    public function siblingNames(): Collection
    {
        return $this->siblings()->map->getName();
    }
}
