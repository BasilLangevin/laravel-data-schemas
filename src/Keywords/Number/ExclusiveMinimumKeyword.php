<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Number;

use BasilLangevin\LaravelDataSchemas\Exception\KeywordValueCouldNotBeInferred;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Validation\GreaterThan;

class ExclusiveMinimumKeyword extends Keyword
{
    public function __construct(public int|float $value) {}

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
        return $schema->merge(['exclusiveMinimum' => $this->value]);
    }

    /**
     * Infer the value of the keyword from the reflector, or throw
     * an exception if the schema should not have this keyword.
     *
     * @throws KeywordValueCouldNotBeInferred
     */
    public static function parse(ReflectionHelper $property): int|float
    {
        if (! $attribute = $property->getAttribute(GreaterThan::class)) {
            throw new KeywordValueCouldNotBeInferred;
        }

        if (! is_numeric($attribute->parameters()[0])) {
            throw new KeywordValueCouldNotBeInferred;
        }

        return $attribute->parameters()[0];
    }
}
