<?php

namespace BasilLangevin\LaravelDataSchemas\Types;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\PropertiesKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\RequiredKeyword;

class ObjectSchema extends Schema
{
    public static DataType $type = DataType::Object;

    public static array $keywords = [
        DescriptionKeyword::class,
        PropertiesKeyword::class,
        RequiredKeyword::class,
    ];
}
