<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;

class IntegerSchema extends NumberSchema
{
    public static DataType $type = DataType::Integer;

    // IntegerSchema inherits its keywords from NumberSchema.
}
