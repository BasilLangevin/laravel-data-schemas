<?php

namespace BasilLangevin\LaravelDataSchemas\Annotators\Contracts;

use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;

interface AnnotatesSchema
{
    public static function annotateSchema(Schema $schema, EntityWrapper $entity): Schema;
}
