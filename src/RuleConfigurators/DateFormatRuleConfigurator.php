<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class DateFormatRuleConfigurator implements ConfiguresStringSchema
{
    public static function configureStringSchema(
        StringSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): StringSchema {
        $formats = $attribute->getValue();

        $formatString = collect($formats)
            ->map(fn (string $format) => sprintf('"%s"', $format))
            ->join(', ', ' or ');

        $annotation = sprintf('The value must match the format %s.', $formatString);

        return $schema->customAnnotation('date-format', $annotation);
    }
}
