<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Schemas\Concerns\HasKeywords;
use Illuminate\Support\Collection;

abstract class Schema
{
    use HasKeywords;

    /**
     * The type that this schema is used on.
     */
    public static DataType $type;

    public function __construct(
        protected string $name = '',
        string $description = '',
    ) {
        if ($description) {
            $this->description($description);
        }
    }

    /**
     * Create a new Schema instance.
     */
    public static function make(string $name = '', string $description = ''): self
    {
        return new static($name, $description);
    }

    /**
     * Set the name of the schema.
     */
    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the name of the schema.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Clone the base structure of the schema.
     */
    public function cloneBaseStructure(): self
    {
        return new static;
    }

    /**
     * Pass the schema to the given callback and return the result.
     */
    public function pipe(callable $callback): self
    {
        return $callback($this);
    }

    /**
     * Apply the given callback when the condition is true.
     */
    public function when(bool $condition, callable $callback): self
    {
        if ($condition) {
            $callback($this);
        }

        return $this;
    }

    /**
     * Convert the schema to an array.
     */
    public function toArray(): array
    {
        return collect(static::$keywords)
            ->flatten()
            ->filter(fn (string $keyword) => $this->hasKeyword($keyword))
            ->reduce(function (Collection $schema, string $keyword) {
                return $this->applyKeyword($keyword, $schema);
            }, collect())
            ->toArray();
    }
}
