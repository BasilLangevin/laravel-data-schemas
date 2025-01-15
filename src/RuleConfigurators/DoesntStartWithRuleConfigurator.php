<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class DoesntStartWithRuleConfigurator implements ConfiguresStringSchema
{
    public static function configureStringSchema(
        StringSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): StringSchema {
        $values = $attribute->getValue();

        $regexValues = collect($values)
            ->map(fn (string $value) => preg_quote($value, '/'))
            ->join('|');

        $pattern = sprintf('/^(?!%s).*$/', $regexValues);

        $list = collect($values)
            ->map(fn ($value) => sprintf('"%s"', $value))
            ->join(', ', ' or ');

        return $schema
            ->pattern($pattern)
            ->customAnnotation([
                'x-doesnt-start-with' => sprintf('The value must not start with %s.', $list),
            ]);
    }
}
