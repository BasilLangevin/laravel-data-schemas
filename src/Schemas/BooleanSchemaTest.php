<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;

covers(BooleanSchema::class);

it('creates a boolean schema')
    ->expect(BooleanSchema::make())
    ->toArray()
    ->toBe([
        'type' => DataType::Boolean->value,
    ]);
