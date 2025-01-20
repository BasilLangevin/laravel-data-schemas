<?php

namespace BasilLangevin\LaravelDataSchemas\Attributes;

use Attribute;
use BasilLangevin\LaravelDataSchemas\Attributes\Contracts\StringAttribute;

/**
 * Adds a title to a Data object or property.
 */
#[Attribute]
class Title implements StringAttribute
{
    public function __construct(protected string $title) {}

    public function getValue(): string
    {
        return $this->title;
    }
}
