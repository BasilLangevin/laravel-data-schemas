<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Types\StringSchema;

covers(StringSchema::class);

it('creates a string schema')
    ->expect(StringSchema::make())
    ->toArray()
    ->toBe([
        'type' => DataType::String->value,
    ]);
