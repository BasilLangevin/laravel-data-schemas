<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Number;

use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class MultipleOfKeyword extends Keyword
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
        return $schema->merge(['multipleOf' => $this->value]);
    }
}
