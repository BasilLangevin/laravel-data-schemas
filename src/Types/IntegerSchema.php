<?php

namespace BasilLangevin\LaravelDataSchemas\Types;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\EnumKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\TitleKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\FormatKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\DefaultKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\MaximumKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\MinimumKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\CustomAnnotationKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\MultipleOfKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\ExclusiveMaximumKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\ExclusiveMinimumKeyword;

class IntegerSchema extends Schema
{
    public static DataType $type = DataType::Integer;

    public static array $keywords = [
        TitleKeyword::class,
        DescriptionKeyword::class,
        CustomAnnotationKeyword::class,
        FormatKeyword::class,
        EnumKeyword::class,
        DefaultKeyword::class,
        MinimumKeyword::class,
        ExclusiveMinimumKeyword::class,
        MaximumKeyword::class,
        ExclusiveMaximumKeyword::class,
        MultipleOfKeyword::class,
    ];
}
