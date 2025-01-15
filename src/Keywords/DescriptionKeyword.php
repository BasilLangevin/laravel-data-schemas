<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords;

use Illuminate\Support\Collection;

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
}
