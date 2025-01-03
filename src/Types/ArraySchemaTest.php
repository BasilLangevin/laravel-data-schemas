<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Types\ArraySchema;

covers(ArraySchema::class);

it('creates a array schema')
    ->expect(ArraySchema::make())
    ->toArray()
    ->toBe([
        'type' => DataType::Array->value,
    ]);
