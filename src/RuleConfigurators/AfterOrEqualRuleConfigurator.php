<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataSchemas\Enums\Format;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Concerns\ParsesDateString;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class AfterOrEqualRuleConfigurator implements ConfiguresStringSchema
{
    use ParsesDateString;

    public static function configureStringSchema(
        StringSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): StringSchema {
        $dateString = self::parseDateString($property, $attribute->getValue());
        $annotation = sprintf('The value must be after or equal to %s.', $dateString);

        return $schema->format(Format::DateTime)
            ->customAnnotation('date-after-or-equal', $annotation);
    }
}
