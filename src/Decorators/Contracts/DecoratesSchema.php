<?php

namespace BasilLangevin\LaravelDataSchemas\Decorators\Contracts;

use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;

interface DecoratesSchema
{
    public static function decorateSchema(Schema $schema, EntityWrapper $entity): Schema;
}
