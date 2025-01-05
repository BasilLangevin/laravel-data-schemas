<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers;

use BasilLangevin\LaravelDataSchemas\Types\IntegerSchema;

class IntegerTransformer extends PropertyTransformer
{
    protected static string $schemaClass = IntegerSchema::class;
}
