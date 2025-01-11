<?php

namespace BasilLangevin\LaravelDataSchemas\Attributes;

use Attribute;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Adds a custom annotation to a Data object or property.
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class CustomAnnotation implements Arrayable
{
    public function __construct(protected string|array $annotation, protected ?string $value = null) {}

    /**
     * Get the custom annotation.
     */
    public function getCustomAnnotation(): array
    {
        return is_array($this->annotation)
            ? $this->annotation
            : [$this->annotation => $this->value];
    }

    /**
     * Get the custom annotation as an array.
     */
    public function toArray(): array
    {
        return $this->getCustomAnnotation();
    }
}
