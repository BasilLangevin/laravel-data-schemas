<?php

namespace BasilLangevin\LaravelDataSchemas\Support;

use BasilLangevin\LaravelDataSchemas\Support\Concerns\AccessesAttributes;
use BasilLangevin\LaravelDataSchemas\Support\Concerns\AccessesDocBlock;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;

class ClassWrapper implements EntityWrapper
{
    use AccessesAttributes;
    use AccessesDocBlock;

    public function __construct(protected ReflectionClass $class) {}

    /**
     * Create a new class wrapper from a class name.
     */
    public static function make(string $className): self
    {
        return new self(new ReflectionClass($className));
    }

    /**
     * Get the name of the class.
     */
    public function getName(): string
    {
        return $this->class->getName();
    }

    /**
     * Get the short name of the class.
     */
    public function getShortName(): string
    {
        return $this->class->getShortName();
    }

    /**
     * Get the properties of the reflector as a collection.
     */
    public function properties(): Collection
    {
        return collect($this->class->getProperties(ReflectionProperty::IS_PUBLIC))
            ->map(function (ReflectionProperty $property) {
                return new PropertyWrapper($property);
            });
    }

    /**
     * Check if the class has a constructor.
     */
    public function hasConstructor(): bool
    {
        return $this->class->hasMethod('__construct');
    }

    /**
     * Get the constructor of the class.
     */
    public function getConstructor(): ?ReflectionMethod
    {
        return $this->class->getMethod('__construct');
    }

    /**
     * Get the doc block for the class' __construct method.
     */
    public function getConstructorDocBlock(): ?DocBlockParser
    {
        if (! $this->hasConstructor()) {
            return null;
        }

        return DocBlockParser::make($this->getConstructor()->getDocComment());
    }
}
