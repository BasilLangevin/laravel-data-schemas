<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\General;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class TypeKeyword extends Keyword
{
    public function __construct(protected string|DataType $value) {}

    /**
     * Get the value of the keyword.
     */
    public function get(): string|DataType
    {
        return $this->value;
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        $type = $this->get();

        if ($type instanceof DataType) {
            $type = $type->value;
        }

        return $schema->merge([
            'type' => $type,
        ]);
    }
}
