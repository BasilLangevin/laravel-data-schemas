<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts;

use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

interface ConfiguresIntegerSchema
{
    public static function configureIntegerSchema(
        IntegerSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): IntegerSchema;
}
