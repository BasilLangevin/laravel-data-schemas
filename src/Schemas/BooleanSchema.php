<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Decoration\CustomAnnotationKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Decoration\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Decoration\TitleKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Generic\ConstKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Decoration\DefaultKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Generic\EnumKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Generic\FormatKeyword;

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
