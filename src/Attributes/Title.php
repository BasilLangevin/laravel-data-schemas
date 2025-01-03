<?php

namespace BasilLangevin\LaravelDataSchemas\Attributes;

use Attribute;

/**
 * Adds a title to a Data object or property.
 */
#[Attribute]
class Title
{
    public function __construct(protected string $title) {}

    /**
     * Get the title.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the title as a string.
     */
    public function __toString(): string
    {
        return $this->title;
    }
}
