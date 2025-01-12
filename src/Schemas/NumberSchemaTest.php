<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;

covers(NumberSchema::class);

it('creates a number schema')
    ->expect(NumberSchema::make())
    ->toArray()
    ->toBe([
        'type' => DataType::Number->value,
    ]);
