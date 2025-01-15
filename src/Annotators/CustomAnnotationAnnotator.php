<?php

namespace BasilLangevin\LaravelDataSchemas\Annotators;

use BasilLangevin\LaravelDataSchemas\Annotators\Contracts\AnnotatesSchema;
use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;

class CustomAnnotationAnnotator implements AnnotatesSchema
{
    public static function annotateSchema(Schema $schema, EntityWrapper $entity): Schema
    {
        $annotations = $entity->attributes(CustomAnnotation::class)
            ->flatMap(fn ($attribute) => $attribute->getCustomAnnotation());

        if ($annotations->isEmpty()) {
            return $schema;
        }

        return $schema->customAnnotations($annotations->toArray());
    }
}
