<?php

namespace BasilLangevin\LaravelDataSchemas\Types;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\PropertiesKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\RequiredKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\TitleKeyword;

class ObjectSchema extends Schema
{
    public static DataType $type = DataType::Object;

    public static array $keywords = [
        TitleKeyword::class,
        DescriptionKeyword::class,
        PropertiesKeyword::class,
        RequiredKeyword::class,
    ];
}
