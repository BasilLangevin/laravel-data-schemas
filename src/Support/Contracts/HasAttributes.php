<?php

namespace BasilLangevin\LaravelDataSchemas\Support\Contracts;

use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use Illuminate\Support\Collection;

interface HasAttributes
{
    /**
     * Get the attributes of the property as a collection of AttributeWrappers.
     */
    public function attributes(?string $name = null): Collection;

    public function hasAttribute(string $name): bool;

    public function getAttribute(string $name): ?AttributeWrapper;
}
