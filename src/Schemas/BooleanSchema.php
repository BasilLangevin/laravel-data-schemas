<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;

class BooleanSchema extends Schema
{
    public static DataType $type = DataType::Boolean;

    public static array $keywords = [
        Keyword::ANNOTATION_KEYWORDS,
        Keyword::GENERAL_KEYWORDS,
    ];
}
