<?php

namespace BasilLangevin\LaravelDataSchemas;

use BasilLangevin\LaravelDataSchemas\Transformers\DataTransformer;
use BasilLangevin\LaravelDataSchemas\Types\Schema;
use ReflectionClass;

class JsonSchema
{
    /**
     * The Spatie Data class to transform into a JSON Schema.
     */
    protected string $dataClass;

    public function __construct() {}

    /**
     * Transform a Spatie Data class into a JSON Schema.
     */
    public function make(string $dataClass): Schema
    {
        return $this->dataClass($dataClass)->build();
    }

    /**
     * Set the Spatie Data class that will be transformed.
     */
    protected function dataClass(string $dataClass): self
    {
        $this->dataClass = $dataClass;

        return $this;
    }

    /**
     * Build the JSON Schema.
     */
    protected function build(): Schema
    {
        return DataTransformer::transform(new ReflectionClass($this->dataClass));
    }
}
