<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresNumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class DigitsBetweenRuleConfigurator implements ConfiguresNumberSchema
{
    public static function configureNumberSchema(
        NumberSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): NumberSchema {
        [$minDigits, $maxDigits] = $attribute->getValue();
        $min = pow(10, $minDigits - 1);
        $max = pow(10, $maxDigits) - 1;

        return $schema->minimum($min)
            ->maximum($max)
            ->customAnnotation('digits-between', sprintf('The value must have between %d and %d digits.', $minDigits, $maxDigits));
    }
}
