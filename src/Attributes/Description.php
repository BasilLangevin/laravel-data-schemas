<?php

namespace BasilLangevin\LaravelDataSchemas\Attributes;

use Attribute;

/**
 * Adds a description to a Data object or property.
 */
#[Attribute]
class Description
{
    public function __construct(protected string $description) {}

    /**
     * Get the description.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get the description as a string.
     */
    public function __toString(): string
    {
        return $this->description;
    }
}
