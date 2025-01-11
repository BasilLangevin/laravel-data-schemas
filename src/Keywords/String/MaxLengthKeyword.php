<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\String;

use BasilLangevin\LaravelDataSchemas\Exception\KeywordValueCouldNotBeInferred;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Attributes\Validation\LessThan;
use Spatie\LaravelData\Attributes\Validation\LessThanOrEqualTo;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Size;

class MaxLengthKeyword extends Keyword
{
    public function __construct(protected int $value) {}

    /**
     * Get the value of the keyword.
     */
    public function get(): int
    {
        return $this->value;
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge(['maxLength' => $this->value]);
    }

    /**
     * Infer the value of the keyword from the reflector, or throw
     * an exception if the schema should not have this keyword.
     *
     * @throws KeywordValueCouldNotBeInferred
     */
    public static function parse(ReflectionHelper $property): int
    {
        $maximums = collect([
            self::parseBetweenAttribute($property),
            self::parseLessThanAttribute($property),
            self::parseLessThanOrEqualToAttribute($property),
            self::parseMaxAttribute($property),
            self::parseSizeAttribute($property),
        ])->filter();

        if ($maximums->isEmpty()) {
            throw new KeywordValueCouldNotBeInferred;
        }

        return $maximums->max();
    }

    /**
     * Parse the maximum value from the between attribute.
     */
    protected static function parseBetweenAttribute(ReflectionHelper $property): ?int
    {
        if (! $attribute = $property->getAttribute(Between::class)) {
            return null;
        }

        return $attribute->parameters()[1];
    }

    /**
     * Parse the maximum value from the less than to attribute.
     */
    protected static function parseLessThanAttribute(ReflectionHelper $property): ?int
    {
        if (! $attribute = $property->getAttribute(LessThan::class)) {
            return null;
        }

        if (! is_numeric($attribute->parameters()[0])) {
            return null;
        }

        return $attribute->parameters()[0] - 1;
    }

    /**
     * Parse the maximum value from the less than or equal to attribute.
     */
    protected static function parseLessThanOrEqualToAttribute(ReflectionHelper $property): ?int
    {
        if (! $attribute = $property->getAttribute(LessThanOrEqualTo::class)) {
            return null;
        }

        if (! is_numeric($attribute->parameters()[0])) {
            return null;
        }

        return $attribute->parameters()[0];
    }

    /**
     * Parse the maximum value from the max attribute.
     */
    protected static function parseMaxAttribute(ReflectionHelper $property): ?int
    {
        if (! $attribute = $property->getAttribute(Max::class)) {
            return null;
        }

        return $attribute->parameters()[0];
    }

    /**
     * Parse the maximum value from the size attribute.
     */
    protected static function parseSizeAttribute(ReflectionHelper $property): ?int
    {
        if (! $attribute = $property->getAttribute(Size::class)) {
            return null;
        }

        return $attribute->parameters()[0];
    }
}
