<?php

namespace BasilLangevin\LaravelDataSchemas\Types;

use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;

class BooleanSchema extends Schema
{
    public static array $keywords = [
        DescriptionKeyword::class,
    ];

    public function toArray(): array
    {
        return [
            'type' => 'boolean',
        ];
    }
}
