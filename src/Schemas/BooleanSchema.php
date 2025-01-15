<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\EnumKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\ConstKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\TitleKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\FormatKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\DefaultKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\CustomAnnotationKeyword;

class BooleanSchema extends Schema
{
    public static DataType $type = DataType::Boolean;

    public static array $keywords = [
        TitleKeyword::class,
        DescriptionKeyword::class,
        CustomAnnotationKeyword::class,
        FormatKeyword::class,
        EnumKeyword::class,
        ConstKeyword::class,
        DefaultKeyword::class,
    ];
}
