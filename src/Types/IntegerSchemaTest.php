<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Types\IntegerSchema;

covers(IntegerSchema::class);

it('creates a integer schema')
    ->expect(IntegerSchema::make())
    ->toArray()
    ->toBe([
        'type' => DataType::Integer->value,
    ]);
