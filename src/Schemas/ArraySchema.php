<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Array\MaxItemsKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Array\MinItemsKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;

class ArraySchema extends Schema
{
    public static DataType $type = DataType::Array;

    public static array $keywords = [
        Keyword::ANNOTATION_KEYWORDS,
        Keyword::GENERAL_KEYWORDS,
        MaxItemsKeyword::class,
        MinItemsKeyword::class,
    ];
}
