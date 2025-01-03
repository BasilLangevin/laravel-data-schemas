<?php

namespace BasilLangevin\LaravelDataSchemas\Types;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\DefaultKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\TitleKeyword;

class IntegerSchema extends Schema
{
    public static DataType $type = DataType::Integer;

    public static array $keywords = [
        TitleKeyword::class,
        DescriptionKeyword::class,
        DefaultKeyword::class,
    ];
}
