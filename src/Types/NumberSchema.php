<?php

namespace BasilLangevin\LaravelDataSchemas\Types;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;

class NumberSchema extends Schema
{
    public static DataType $type = DataType::Number;

    public static array $keywords = [
        DescriptionKeyword::class,
    ];
}
