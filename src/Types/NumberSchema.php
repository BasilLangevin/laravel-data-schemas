<?php

namespace BasilLangevin\LaravelDataSchemas\Types;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\TitleKeyword;

class NumberSchema extends Schema
{
    public static DataType $type = DataType::Number;

    public static array $keywords = [
        TitleKeyword::class,
        DescriptionKeyword::class,
    ];
}
