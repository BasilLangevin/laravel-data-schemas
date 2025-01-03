<?php

namespace BasilLangevin\LaravelDataSchemas\Types;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;

class StringSchema extends Schema
{
    public static DataType $type = DataType::String;

    public static array $keywords = [
        DescriptionKeyword::class,
    ];
}
