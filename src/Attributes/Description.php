<?php

namespace BasilLangevin\LaravelDataSchemas\Attributes;

use Attribute;
use BasilLangevin\LaravelDataSchemas\Attributes\Contracts\StringAttribute;

/**
 * Adds a description to a Data object or property.
 */
#[Attribute]
class Description implements StringAttribute
{
    public function __construct(protected string $description) {}

    public function getValue(): string
    {
        return $this->description;
    }
}
