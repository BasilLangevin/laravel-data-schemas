<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Object\MaxPropertiesKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Object\MinPropertiesKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Object\PropertiesKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Object\RequiredKeyword;

class ObjectSchema extends Schema
{
    public static DataType $type = DataType::Object;

    public static array $keywords = [
        Keyword::ANNOTATION_KEYWORDS,
        Keyword::GENERAL_KEYWORDS,
        PropertiesKeyword::class,
        RequiredKeyword::class,
        MaxPropertiesKeyword::class,
        MinPropertiesKeyword::class,
    ];
}
