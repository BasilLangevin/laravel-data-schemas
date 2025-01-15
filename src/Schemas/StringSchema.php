<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\EnumKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\ConstKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\TitleKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\FormatKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\DefaultKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\String\PatternKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\CustomAnnotationKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\String\MaxLengthKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\String\MinLengthKeyword;

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
