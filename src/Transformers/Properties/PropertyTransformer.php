<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers\Properties;

use BasilLangevin\LaravelDataSchemas\Attributes\Description;
use BasilLangevin\LaravelDataSchemas\Transformers\DocBlockParser;
use BasilLangevin\LaravelDataSchemas\Types\Schema;
use ReflectionProperty;

abstract class PropertyTransformer
{
    /**
     * The property that the transformer is building a Schema object for.
     */
    protected ReflectionProperty $property;

    /**
     * The DocBlockParser parsers for the property, constructor, and class.
     */
    protected ?DocBlockParser $propertyDoc;

    protected ?DocBlockParser $constructorDoc;

    protected ?DocBlockParser $classDoc;

    /**
     * The Schema object that the transformer builds.
     */
    protected Schema $schema;

    /**
     * The class name of the Schema object that the transformer builds.
     */
    protected static string $schemaClass;

    /**
     * Create a new PropertyTransformer instance.
     */
    public function __construct(ReflectionProperty $property)
    {
        $this->property = $property;
        $this->schema = static::$schemaClass::make($property->getName());

        $this->propertyDoc = DocBlockParser::make($property->getDocComment());

        $this->constructorDoc = DocBlockParser::make(
            $property->getDeclaringClass()->getMethod('__construct')?->getDocComment()
        );

        $this->classDoc = DocBlockParser::make($property->getDeclaringClass()->getDocComment());
    }

    /**
     * Transform a ReflectionProperty into a Schema object.
     */
    public static function transform(ReflectionProperty $property): Schema
    {
        return match ($property->getType()->getName()) {
            'bool' => BooleanTransformer::transform($property),
        };
    }

    /**
     * Get the Schema object.
     */
    protected function getSchema(): Schema
    {
        return $this->schema;
    }

    /**
     * Check if the property has a specific attribute.
     */
    protected function hasAttribute(string $attribute): bool
    {
        return $this->property->getAttributes($attribute) !== [];
    }

    /**
     * Get an attribute from the property.
     */
    protected function getAttribute(string $attribute): ?object
    {
        if (! $this->hasAttribute($attribute)) {
            return null;
        }

        return $this->property->getAttributes($attribute)[0]
            ->newInstance();
    }

    /**
     * Get the description of the property.
     */
    protected function getDocBlockDescription(): string
    {
        $name = $this->property->getName();

        return $this->propertyDoc?->getParamDescription($name)
            ?? $this->propertyDoc?->getVarDescription()
            ?? $this->propertyDoc?->getSummary()
            ?? $this->constructorDoc?->getParamDescription($name)
            ?? $this->classDoc?->getVarDescription($name)
            ?? '';
    }

    /**
     * Add a description to the property.
     */
    protected function addDescription(): static
    {
        // The Description attribute always takes precedence.
        if ($attribute = $this->getAttribute(Description::class)) {
            $this->schema->description($attribute);

            return $this;
        }

        if ($description = $this->getDocBlockDescription()) {
            $this->schema->description($description);

            return $this;
        }

        return $this;
    }
}
