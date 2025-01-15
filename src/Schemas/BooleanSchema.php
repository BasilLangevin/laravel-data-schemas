<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\CustomAnnotationKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\DefaultKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\TitleKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\General\ConstKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\General\EnumKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\General\FormatKeyword;

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
