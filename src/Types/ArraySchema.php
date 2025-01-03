<?php

namespace BasilLangevin\LaravelDataSchemas\Types;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;

class ArraySchema extends Schema
{
    public static DataType $type = DataType::Array;

    public static array $keywords = [
        DescriptionKeyword::class,
    ];
}
