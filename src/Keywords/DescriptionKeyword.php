<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords;

use BasilLangevin\LaravelDataSchemas\Attributes\Description;
use BasilLangevin\LaravelDataSchemas\Transformers\DocBlockParser;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionProperty;

class DescriptionKeyword extends Keyword
{
    public function __construct(protected string $value) {}

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
            'description' => $this->get(),
        ]);
    }

    /**
     * Infer the value of the keyword from the property, or return
     * null if the property schema should not have this keyword.
     */
    public static function parse(ReflectionHelper $property): ?string
    {
        if ($attribute = $property->getAttribute(Description::class)) {
            return $attribute->getDescription();
        }

        return match ($property->getReflectorClassName()) {
            ReflectionProperty::class => static::parseProperty($property),
            ReflectionClass::class => static::parseClass($property),
        };
    }

    /**
     * Parse the description from the property.
     */
    protected static function parseProperty(ReflectionHelper $property): ?string
    {
        [$propertyDoc, $constructorDoc, $classDoc] = static::getPropertyDocBlocks($property);

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
     * Get the doc blocks for the property.
     */
    protected static function getPropertyDocBlocks(ReflectionHelper $property): array
    {
        $class = $property->getDeclaringClass();

        return [
            DocBlockParser::make($property->getDocComment()),
            DocBlockParser::make($class->hasMethod('__construct') ? $class->getMethod('__construct')->getDocComment() : null),
            DocBlockParser::make($class->getDocComment()),
        ];
    }

    /**
     * Parse the description from the class.
     */
    protected static function parseClass(ReflectionHelper $class): ?string
    {
        $docBlock = DocBlockParser::make($class->getDocComment());

        return $docBlock?->getDescription()
            ?? $docBlock?->getSummary()
            ?? null;
    }
}
