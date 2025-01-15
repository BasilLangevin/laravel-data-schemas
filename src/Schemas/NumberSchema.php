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
use BasilLangevin\LaravelDataSchemas\Keywords\Number\ExclusiveMaximumKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\ExclusiveMinimumKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\MaximumKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\MinimumKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\MultipleOfKeyword;

class NumberSchema extends Schema
{
    public static DataType $type = DataType::Number;

    public static array $keywords = [
        TitleKeyword::class,
        DescriptionKeyword::class,
        CustomAnnotationKeyword::class,
        FormatKeyword::class,
        EnumKeyword::class,
        ConstKeyword::class,
        DefaultKeyword::class,
        MinimumKeyword::class,
        ExclusiveMinimumKeyword::class,
        MaximumKeyword::class,
        ExclusiveMaximumKeyword::class,
        MultipleOfKeyword::class,
    ];
}
