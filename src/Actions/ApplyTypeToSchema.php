<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class ApplyTypeToSchema
{
    use Runnable;

    public function handle(Schema $schema, PropertyWrapper $property): Schema
    {
        return $schema->type($schema::$type);
    }
}
