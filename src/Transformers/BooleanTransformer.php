<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers;

use BasilLangevin\LaravelDataSchemas\Types\BooleanSchema;

class BooleanTransformer extends PropertyTransformer
{
    protected static string $schemaClass = BooleanSchema::class;
}
