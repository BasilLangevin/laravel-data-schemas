<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Generic;

use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class ConstKeyword extends Keyword
{
    public function __construct(protected mixed $value) {}

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
            'const' => $this->get(),
        ]);
    }
}
