<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers;

use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;

class BooleanTransformer extends PropertyTransformer
{
    protected static string $schemaClass = BooleanSchema::class;
}
