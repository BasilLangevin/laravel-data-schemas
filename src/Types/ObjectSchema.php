<?php

namespace BasilLangevin\LaravelDataSchemas\Types;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\TitleKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\FormatKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\DefaultKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Object\RequiredKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\CustomAnnotationKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Object\PropertiesKeyword;

class ObjectSchema extends Schema
{
    public static DataType $type = DataType::Object;

    public static array $keywords = [
        TitleKeyword::class,
        DescriptionKeyword::class,
        CustomAnnotationKeyword::class,
        FormatKeyword::class,
        DefaultKeyword::class,
        PropertiesKeyword::class,
        RequiredKeyword::class,
    ];
}
