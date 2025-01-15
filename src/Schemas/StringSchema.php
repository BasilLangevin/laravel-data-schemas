<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\CustomAnnotationKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\DefaultKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\TitleKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Generic\ConstKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Generic\EnumKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Generic\FormatKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\String\MaxLengthKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\String\MinLengthKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\String\PatternKeyword;

class StringSchema extends Schema
{
    public static DataType $type = DataType::String;

    public static array $keywords = [
        TitleKeyword::class,
        DescriptionKeyword::class,
        CustomAnnotationKeyword::class,
        FormatKeyword::class,
        EnumKeyword::class,
        ConstKeyword::class,
        DefaultKeyword::class,
        MinLengthKeyword::class,
        MaxLengthKeyword::class,
        PatternKeyword::class,
    ];
}
