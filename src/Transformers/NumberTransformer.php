<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers;

use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;

class NumberTransformer extends PropertyTransformer
{
    protected static string $schemaClass = NumberSchema::class;
}
