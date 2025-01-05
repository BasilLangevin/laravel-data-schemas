<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers;

use BasilLangevin\LaravelDataSchemas\Types\NumberSchema;

class NumberTransformer extends PropertyTransformer
{
    protected static string $schemaClass = NumberSchema::class;
}
