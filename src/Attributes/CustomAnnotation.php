<?php

namespace BasilLangevin\LaravelDataSchemas\Attributes;

use Attribute;
use BasilLangevin\LaravelDataSchemas\Attributes\Contracts\ArrayAttribute;

/**
 * Adds a custom annotation to a Data object or property.
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class CustomAnnotation implements ArrayAttribute
{
    public function __construct(protected string|array $annotation, protected ?string $value = null) {}

    public function getValue(): array
    {
        return is_array($this->annotation)
            ? $this->annotation
            : [$this->annotation => $this->value];
    }
}
