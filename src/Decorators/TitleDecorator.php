<?php

namespace BasilLangevin\LaravelDataSchemas\Decorators;

use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\Decorators\Contracts\DecoratesSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;

class TitleDecorator implements DecoratesSchema
{
    public static function decorateSchema(Schema $schema, EntityWrapper $entity): Schema
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

        if (! $docBlock->hasSummary()) {
            return $schema;
        }

        return $schema->title($docBlock->getSummary());
    }
}
