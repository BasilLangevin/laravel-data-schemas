<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators\Concerns;

use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;

trait ResolvesPropertyName
{
    protected static function resolvePropertyName(AttributeWrapper $attribute): string
    {
        return $attribute->getValue()->name;
    }
}
