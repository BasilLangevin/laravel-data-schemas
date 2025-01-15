<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class ApplyEnumToSchema
{
    use Runnable;

    public function handle(Schema $schema, PropertyWrapper $property): Schema
    {
        $enum = $property->getType()->getName();

        return $schema->enum($enum);
    }
}
