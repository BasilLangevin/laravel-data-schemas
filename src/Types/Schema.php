<?php

namespace BasilLangevin\LaravelDataSchemas\Types;

use BasilLangevin\LaravelDataSchemas\Concerns\HasKeywords;
use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Exceptions\KeywordValueCouldNotBeInferred;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
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
        return new static($name, $description);
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
     * Add any keywords that can be inferred from the reflection object.
     */
    public function resolveKeywords(ReflectionHelper $reflector): static
    {
        collect(static::$keywords)
            ->each(fn (string $keyword) => $this->resolveKeyword($keyword, $reflector));

        return $this;
    }

    /**
     * Add a keyword if its value can be inferred.
     *
     * Because we need to be able to set null values, the parse
     * method throws an exception if no value can be inferred.
     */
    protected function resolveKeyword(string $keyword, ReflectionHelper $reflector): self
    {
        try {
            $value = $keyword::parse($reflector);
        } catch (KeywordValueCouldNotBeInferred $e) {
            return $this;
        }

        return $this->setKeyword($keyword, $value);
    }

    /**
     * Convert the schema to an array.
     */
    public function toArray(): array
    {
        $schema = collect([
            'type' => static::$type->value,
        ]);

        return collect(static::$keywords)
            ->filter(fn (string $keyword) => $this->hasKeyword($keyword))
            ->reduce(function (Collection $schema, string $keyword) {
                return $this->applyKeyword($keyword, $schema);
            }, $schema)
            ->toArray();
    }
}
