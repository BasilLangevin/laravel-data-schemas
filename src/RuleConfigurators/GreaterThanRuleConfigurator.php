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

class GreaterThanRuleConfigurator implements ConfiguresArraySchema, ConfiguresNumberSchema, ConfiguresObjectSchema, ConfiguresStringSchema
{
    use ResolvesPropertyName;

    public static function configureArraySchema(
        ArraySchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): ArraySchema {
        if (is_int($attribute->getValue())) {
            return $schema->minItems($attribute->getValue() + 1);
        }

        $property = self::resolvePropertyName($attribute);

        return $schema->customAnnotation('x-greater-than', sprintf('The value must have more items than the %s property.', $property));
    }

    public static function configureNumberSchema(
        NumberSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): NumberSchema {
        if (is_int($attribute->getValue())) {
            return $schema->exclusiveMinimum($attribute->getValue());
        }

        $property = self::resolvePropertyName($attribute);

        return $schema->customAnnotation('x-greater-than', sprintf('The value must be greater than the value of %s.', $property));
    }

    public static function configureObjectSchema(
        ObjectSchema $schema,
        EntityWrapper $entity,
        AttributeWrapper $attribute
    ): ObjectSchema {
        if (is_int($attribute->getValue())) {
            return $schema->minProperties($attribute->getValue() + 1);
        }

        $property = self::resolvePropertyName($attribute);

        return $schema->customAnnotation('x-greater-than', sprintf('The value must have more properties than the %s property.', $property));
    }

    public static function configureStringSchema(
        StringSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): StringSchema {
        if (is_int($attribute->getValue())) {
            return $schema->minLength($attribute->getValue() + 1);
        }

        $property = self::resolvePropertyName($attribute);

        return $schema->customAnnotation('x-greater-than', sprintf('The value must have more characters than the value of %s.', $property));
    }
}
