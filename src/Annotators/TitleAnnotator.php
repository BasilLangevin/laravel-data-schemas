<?php

namespace BasilLangevin\LaravelDataSchemas\Annotators;

use BasilLangevin\LaravelDataSchemas\Annotators\Contracts\AnnotatesSchema;
use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;

class TitleAnnotator implements AnnotatesSchema
{
    public static function annotateSchema(Schema $schema, EntityWrapper $entity): Schema
    {
        if ($entity->hasAttribute(Title::class)) {
            return $schema->title($entity->getAttribute(Title::class)->getValue());
        }

        if (! $docBlock = $entity->getDocBlock()) {
            return $schema;
        }

        // The doc block summary only becomes the title if it also has a description.
        if (! $docBlock->hasDescription()) {
            return $schema;
        }

        return $schema->title($docBlock->getSummary());
    }
}
