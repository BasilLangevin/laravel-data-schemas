<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords;

use BasilLangevin\LaravelDataSchemas\Exceptions\SchemaConfigurationException;
use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\HandlesMultipleInstances;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Validation\Enum;

class EnumKeyword extends Keyword implements HandlesMultipleInstances
{
    public function __construct(protected string|array $value)
    {
        if (is_array($value)) {
            return;
        }

        if (! enum_exists($value)) {
            throw new \InvalidArgumentException("Enum '{$value}' is not a valid enum.");
        }

        $reflection = new \ReflectionEnum($value);

        if (! $reflection->isBacked()) {
            throw new \InvalidArgumentException("Enum '{$value}' is not a backed enum. Only backed enums are supported.");
        }
    }

    /**
     * Get the value of the keyword.
     */
    public function get(): string|array
    {
        return $this->value;
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        $keywords = array_filter([
            'enum' => $this->getValues(),
            'x-enum-values' => $this->getValueDescriptions(),
        ]);

        return $schema->merge($keywords);
    }

    /**
     * Check if the value is an enum.
     */
    protected function isEnum(): bool
    {
        return is_string($this->get()) && enum_exists($this->get());
    }

    /**
     * Get the values for the keyword.
     */
    protected function getValues(): array
    {
        $values = $this->isEnum() ? $this->get()::cases() : $this->get();

        return collect($values)->map(function ($value) {
            if (! is_object($value)) {
                return $value;
            }

            if (property_exists($value, 'value')) {
                return $value->value;
            }

            throw new SchemaConfigurationException('Non-backed enum values are not supported.');
        })->toArray();
    }

    /**
     * Get the value descriptions for the enum if the enum backing type is an integer.
     */
    protected function getValueDescriptions(): ?array
    {
        if (! $this->isEnum()) {
            return null;
        }

        $reflection = new \ReflectionEnum($this->get());

        if ($reflection->getBackingType()->getName() !== 'int') {
            return null;
        }

        return collect($this->get()::cases())->mapWithKeys(function ($value) {
            return [$value->name => $value->value];
        })->toArray();
    }

    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        $values = $instances->map(fn ($instance) => $instance->getValues());

        $commonValues = $values->skip(1)
            ->reduce(function (Collection $result, array $instanceValues) {
                return $result->intersect($instanceValues);
            }, collect($values->first()))
            ->values();

        if ($commonValues->isEmpty()) {
            throw new SchemaConfigurationException('Multiple enums were set with no overlapping values.');
        }

        return $schema->merge([
            'enum' => $commonValues->toArray(),
        ]);
    }
}
