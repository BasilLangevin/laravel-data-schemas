<?php

namespace BasilLangevin\LaravelDataSchemas\Decorators;

use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataSchemas\Decorators\Contracts\DecoratesSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;

class CustomAnnotationDecorator implements DecoratesSchema
{
    public static function decorateSchema(Schema $schema, EntityWrapper $entity): Schema
    {
        $annotations = $entity->attributes(CustomAnnotation::class)
            ->flatMap(fn ($attribute) => $attribute->getCustomAnnotation());

        if ($annotations->isEmpty()) {
            return $schema;
        }

        return $schema->customAnnotations($annotations->toArray());
    }
}
