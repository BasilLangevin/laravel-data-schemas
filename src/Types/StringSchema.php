<?php

namespace BasilLangevin\LaravelDataSchemas\Types;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\DefaultKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\String\MaxLengthKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\String\MinLengthKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\TitleKeyword;

class StringSchema extends Schema
{
    public static DataType $type = DataType::String;

    public static array $keywords = [
        TitleKeyword::class,
        DescriptionKeyword::class,
        DefaultKeyword::class,
        MinLengthKeyword::class,
        MaxLengthKeyword::class,
    ];
}
