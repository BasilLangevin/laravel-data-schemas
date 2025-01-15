<?php

namespace BasilLangevin\LaravelDataSchemas\Support;

use BasilLangevin\LaravelDataSchemas\Support\Concerns\AccessesAttributes;
use BasilLangevin\LaravelDataSchemas\Support\Concerns\AccessesDocBlock;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;
use Illuminate\Support\Collection;
use ReflectionNamedType;
use ReflectionProperty;

class PropertyWrapper implements EntityWrapper
{
    use AccessesAttributes;
    use AccessesDocBlock;

    public function __construct(protected ReflectionProperty $property) {}

    /**
     * Create a new property wrapper from a reflection property.
     */
    public static function make(string $className, string $propertyName): self
    {
        return new self(new ReflectionProperty($className, $propertyName));
    }

    public function getType(): ReflectionNamedType
    {
        return $this->property->getType();
    }

    /**
     * Determine if the property has a type.
     */
    public function hasType(string $type): bool
    {
        return match ($type) {
            '*' => true,
            'array' => $this->isArray(),
            'boolean' => $this->isBoolean(),
            'integer' => $this->isInteger(),
            'number' => $this->isNumber(),
            'object' => $this->isObject(),
            'string' => $this->isString(),
            default => false,
        };
    }

    /**
     * Determine if the reflected property is an array.
     */
    public function isArray(): bool
    {
        return $this->property->getType()->getName() === 'array';
    }

    /**
     * Determine if the reflected property is a boolean.
     */
    public function isBoolean(): bool
    {
        return $this->property->getType()->getName() === 'bool';
    }

    /**
     * Determine if the reflected property is an enum.
     */
    public function isEnum(): bool
    {
        return enum_exists($this->property->getType()->getName());
    }

    /**
     * Determine if the reflected property is a float.
     */
    public function isFloat(): bool
    {
        return $this->property->getType()->getName() === 'float';
    }

    /**
     * Determine if the reflected property is an integer.
     */
    public function isInteger(): bool
    {
        return $this->property->getType()->getName() === 'int';
    }

    /**
     * Determine if the reflected property is a number.
     */
    public function isNumber(): bool
    {
        return in_array($this->property->getType()->getName(), ['float', 'int']);
    }

    /**
     * Determine if the reflected property is an object.
     */
    public function isObject(): bool
    {
        return $this->property->getType()->getName() === 'object';
    }

    /**
     * Determine if the reflected property is a string.
     */
    public function isString(): bool
    {
        return $this->property->getType()->getName() === 'string';
    }

    /**
     * Get the name of the property.
     */
    public function getName(): string
    {
        return $this->property->getName();
    }

    /**
     * Determine if the property has a default value.
     */
    public function hasDefaultValue(): bool
    {
        return $this->property->hasDefaultValue();
    }

    /**
     * Get the default value of the property.
     */
    public function getDefaultValue(): mixed
    {
        return $this->property->getDefaultValue();
    }

    /**
     * Get the declaring class of the property as a ClassWrapper.
     */
    public function getClass(): ClassWrapper
    {
        return new ClassWrapper($this->property->getDeclaringClass());
    }

    /**
     * Get the siblings of the property as a collection.
     */
    public function siblings(): Collection
    {
        return collect($this->getClass()->properties())
            ->filter(function (PropertyWrapper $property) {
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
