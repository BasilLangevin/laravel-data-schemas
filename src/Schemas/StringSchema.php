<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Keywords\String\MaxLengthKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\String\MinLengthKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\String\PatternKeyword;

class StringSchema extends Schema
{
    public static DataType $type = DataType::String;

    public static array $keywords = [
        Keyword::ANNOTATION_KEYWORDS,
        Keyword::GENERAL_KEYWORDS,
        MaxLengthKeyword::class,
        MinLengthKeyword::class,
        PatternKeyword::class,
    ];
}
