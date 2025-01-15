<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Object;

use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class RequiredKeyword extends Keyword implements HandlesMultipleInstances
{
    public function __construct(protected array $value) {}

    /**
     * Get the value of the keyword.
     */
    public function get(): array
    {
        return $this->value;
    }

    /**
     * Apply the keyword to the schema.
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge([
            'required' => $this->get(),
        ]);
    }

    /**
     * Apply multiple instances of the keyword to the schema.
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        return $schema->merge([
            'required' => $instances->flatMap->get()->unique()->values()->all(),
        ]);
    }
}
