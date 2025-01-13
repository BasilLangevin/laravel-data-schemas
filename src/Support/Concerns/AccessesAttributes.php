<?php

namespace BasilLangevin\LaravelDataSchemas\Support\Concerns;

use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use Illuminate\Support\Collection;

trait AccessesAttributes
{
    /**
     * Get the attributes of the property as a collection of AttributeWrappers.
     */
    public function attributes(?string $name = null): Collection
    {
        $reflector = $this instanceof ClassWrapper ? $this->class : $this->property;

        return collect($reflector->getAttributes($name))
            ->map(fn (\ReflectionAttribute $attribute) => new AttributeWrapper($attribute));
    }

    public function hasAttribute(string $name): bool
    {
        return $this->attributes($name)->isNotEmpty();
    }

    public function getAttribute(string $name): ?AttributeWrapper
    {
        return $this->attributes($name)->first();
    }
}
