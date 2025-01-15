<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Decoration;

use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CustomAnnotationKeyword extends Keyword implements HandlesMultipleInstances
{
    public function __construct(protected string|array $annotation, protected ?string $value = null) {}

    /**
     * Get the value of the keyword.
     */
    public function get(): array
    {
        $annotations = is_array($this->annotation)
            ? $this->annotation
            : [$this->annotation => $this->value];

        return collect($annotations)
            ->mapWithKeys(fn ($value, $key) => [Str::start($key, 'x-') => $value])
            ->toArray();
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge($this->get());
    }

    /**
     * Apply multiple instances of the keyword to the schema.
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        return $schema->merge($instances->flatMap->get());
    }
}
