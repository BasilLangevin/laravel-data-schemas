<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords;

use Illuminate\Support\Collection;

class RequiredKeyword extends Keyword
{
    public function __construct(protected array $value) {}

    public function get(): array
    {
        return $this->value;
    }

    public function apply(Collection $schema): Collection
    {
        return $schema->merge([
            'required' => $this->get(),
        ]);
    }
}
