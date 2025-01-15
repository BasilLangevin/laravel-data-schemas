<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresAnySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;

class ConfirmedRuleConfigurator implements ConfiguresAnySchema
{
    public static function configureSchema(
        Schema $schema,
        EntityWrapper $entity,
        AttributeWrapper $attribute
    ): Schema {
        $matchedProperty = $entity->getName().'_confirmed';

        return $schema->customAnnotation(
            'matches',
            sprintf('The value must be the same as the value of the %s property.', $matchedProperty),
        );
    }
}
