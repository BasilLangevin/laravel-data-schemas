<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators\Concerns;

use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use Spatie\LaravelData\Support\Validation\References\FieldReference;

trait ResolvesPropertyName
{
    protected static function resolvePropertyName(AttributeWrapper $attribute): string
    {
        $value = $attribute->getValue();

        if ($value instanceof FieldReference) {
            return $value->name;
        }

        return $value;
    }
}
