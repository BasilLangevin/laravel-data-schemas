<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Decoration\CustomAnnotationKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Decoration\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Decoration\TitleKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Generic\ConstKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Decoration\DefaultKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Generic\FormatKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Object\PropertiesKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Object\RequiredKeyword;

class ObjectSchema extends Schema
{
    public static DataType $type = DataType::Object;

    public static array $keywords = [
        TitleKeyword::class,
        DescriptionKeyword::class,
        CustomAnnotationKeyword::class,
        FormatKeyword::class,
        ConstKeyword::class,
        DefaultKeyword::class,
        PropertiesKeyword::class,
        RequiredKeyword::class,
    ];
}
