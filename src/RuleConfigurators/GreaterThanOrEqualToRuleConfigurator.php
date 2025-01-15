<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Concerns\ResolvesPropertyName;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresArraySchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresNumberSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresObjectSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class GreaterThanOrEqualToRuleConfigurator implements ConfiguresArraySchema, ConfiguresNumberSchema, ConfiguresObjectSchema, ConfiguresStringSchema
{
    use ResolvesPropertyName;

    public static function configureArraySchema(
        ArraySchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): ArraySchema {
        if (is_int($attribute->getValue())) {
            return $schema->minItems($attribute->getValue());
        }

        $property = self::resolvePropertyName($attribute);

        return $schema->customAnnotation('x-greater-than-or-equal-to', sprintf('The value must have at least as many items as the %s property.', $property));
    }

    public static function configureNumberSchema(
        NumberSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): NumberSchema {
        if (is_int($attribute->getValue())) {
            return $schema->minimum($attribute->getValue());
        }

        $property = self::resolvePropertyName($attribute);

        return $schema->customAnnotation('x-greater-than-or-equal-to', sprintf('The value must be greater than or equal to the value of %s.', $property));
    }

    public static function configureObjectSchema(
        ObjectSchema $schema,
        EntityWrapper $entity,
        AttributeWrapper $attribute
    ): ObjectSchema {
        if (is_int($attribute->getValue())) {
            return $schema->minProperties($attribute->getValue());
        }

        $property = self::resolvePropertyName($attribute);

        return $schema->customAnnotation('x-greater-than-or-equal-to', sprintf('The value must have at least as many properties as the %s property.', $property));
    }

    public static function configureStringSchema(
        StringSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): StringSchema {
        if (is_int($attribute->getValue())) {
            return $schema->minLength($attribute->getValue());
        }

        $property = self::resolvePropertyName($attribute);

        return $schema->customAnnotation('x-greater-than-or-equal-to', sprintf('The value must have at least as many characters as the value of %s.', $property));
    }
}
