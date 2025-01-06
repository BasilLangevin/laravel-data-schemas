<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers;

use BasilLangevin\LaravelDataSchemas\Types\StringSchema;

class StringTransformer extends PropertyTransformer
{
    protected static string $schemaClass = StringSchema::class;
}
