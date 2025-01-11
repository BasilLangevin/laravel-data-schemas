<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Number;

use BasilLangevin\LaravelDataSchemas\Exception\KeywordValueCouldNotBeInferred;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Attributes\Validation\LessThanOrEqualTo;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\MaxDigits;
use Spatie\LaravelData\Attributes\Validation\Size;

class MaximumKeyword extends Keyword
{
    public function __construct(protected int|float $value) {}

    /**
     * Get the value of the keyword.
     */
    public function get(): int|float
    {
        return $this->value;
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge(['maximum' => $this->value]);
    }

    /**
     * Infer the value of the keyword from the reflector, or throw
     * an exception if the schema should not have this keyword.
     *
     * @throws KeywordValueCouldNotBeInferred
     */
    public static function parse(ReflectionHelper $property): int|float
    {
        $maximums = collect([
            self::parseBetweenAttribute($property),
            self::parseLessThanOrEqualToAttribute($property),
            self::parseMaxAttribute($property),
            self::parseMaxDigitsAttribute($property),
            self::parseSizeAttribute($property),
        ])->filter();

        if ($maximums->isEmpty()) {
            throw new KeywordValueCouldNotBeInferred;
        }

        return $maximums->min();
    }

    /**
     * Parse the maximum value from the between attribute.
     */
    protected static function parseBetweenAttribute(ReflectionHelper $property): int|float|null
    {
        if (! $attribute = $property->getAttribute(Between::class)) {
            return null;
        }

        return $attribute->parameters()[1];
    }

    /**
     * Parse the maximum value from the less than or equal to attribute.
     */
    protected static function parseLessThanOrEqualToAttribute(ReflectionHelper $property): int|float|null
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
     * Parse the maximum value from the min attribute.
     */
    protected static function parseMaxAttribute(ReflectionHelper $property): int|float|null
    {
        if (! $attribute = $property->getAttribute(Max::class)) {
            return null;
        }

        return $attribute->parameters()[0];
    }

    /**
     * Parse the maximum value from the min digits attribute.
     */
    protected static function parseMaxDigitsAttribute(ReflectionHelper $property): int|float|null
    {
        if (! $attribute = $property->getAttribute(MaxDigits::class)) {
            return null;
        }

        return 10 ** ($attribute->parameters()[0]) - 1;
    }

    /**
     * Parse the maximum value from the size attribute.
     */
    protected static function parseSizeAttribute(ReflectionHelper $property): int|float|null
    {
        if (! $attribute = $property->getAttribute(Size::class)) {
            return null;
        }

        return $attribute->parameters()[0];
    }
}
