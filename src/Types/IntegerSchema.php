<?php

namespace BasilLangevin\LaravelDataSchemas\Types;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;

class IntegerSchema extends Schema
{
    public static DataType $type = DataType::Integer;

    public static array $keywords = [
        DescriptionKeyword::class,
    ];
}
