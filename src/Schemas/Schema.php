<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Schemas\Concerns\HasKeywords;
use Illuminate\Support\Collection;

abstract class Schema implements \EchoLabs\Prism\Contracts\Schema
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
        return (new static($name, $description))
            ->type(static::$type);
    }

    /**
     * Get the name of the schema.
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the schema.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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
