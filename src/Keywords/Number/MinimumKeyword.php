<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Number;

use BasilLangevin\LaravelDataSchemas\Exception\KeywordValueCouldNotBeInferred;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Attributes\Validation\GreaterThanOrEqualTo;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\MinDigits;
use Spatie\LaravelData\Attributes\Validation\Size;

class MinimumKeyword extends Keyword
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
        return $schema->merge(['minimum' => $this->value]);
    }

    /**
     * Infer the value of the keyword from the reflector, or throw
     * an exception if the schema should not have this keyword.
     *
     * @throws KeywordValueCouldNotBeInferred
     */
    public static function parse(ReflectionHelper $property): int|float
    {
        $minimums = collect([
            self::parseBetweenAttribute($property),
            self::parseGreaterThanOrEqualToAttribute($property),
            self::parseMinAttribute($property),
            self::parseMinDigitsAttribute($property),
            self::parseSizeAttribute($property),
        ])->filter();

        if ($minimums->isEmpty()) {
            throw new KeywordValueCouldNotBeInferred;
        }

        return $minimums->max();
    }

    /**
     * Parse the minimum value from the between attribute.
     */
    protected static function parseBetweenAttribute(ReflectionHelper $property): int|float|null
    {
        if (! $attribute = $property->getAttribute(Between::class)) {
            return null;
        }

        return $attribute->parameters()[0];
    }

    /**
     * Parse the minimum value from the greater than or equal to attribute.
     */
    protected static function parseGreaterThanOrEqualToAttribute(ReflectionHelper $property): int|float|null
    {
        if (! $attribute = $property->getAttribute(GreaterThanOrEqualTo::class)) {
            return null;
        }

        if (! is_numeric($attribute->parameters()[0])) {
            return null;
        }

        return $attribute->parameters()[0];
    }

    /**
     * Parse the minimum value from the min attribute.
     */
    protected static function parseMinAttribute(ReflectionHelper $property): int|float|null
    {
        if (! $attribute = $property->getAttribute(Min::class)) {
            return null;
        }

        return $attribute->parameters()[0];
    }

    /**
     * Parse the minimum value from the min digits attribute.
     */
    protected static function parseMinDigitsAttribute(ReflectionHelper $property): int|float|null
    {
        if (! $attribute = $property->getAttribute(MinDigits::class)) {
            return null;
        }

        return 10 ** ($attribute->parameters()[0] - 1);
    }

    /**
     * Parse the minimum value from the size attribute.
     */
    protected static function parseSizeAttribute(ReflectionHelper $property): int|float|null
    {
        if (! $attribute = $property->getAttribute(Size::class)) {
            return null;
        }

        return $attribute->parameters()[0];
    }
}
