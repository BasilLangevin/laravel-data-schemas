<?php

namespace BasilLangevin\LaravelDataSchemas\Types;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;

class BooleanSchema extends Schema
{
    public static DataType $type = DataType::Boolean;

    public static array $keywords = [
        DescriptionKeyword::class,
    ];
}
