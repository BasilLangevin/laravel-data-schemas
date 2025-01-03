<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Types\ObjectSchema;

covers(ObjectSchema::class);

it('creates an object schema')
    ->expect(ObjectSchema::make())
    ->toArray()
    ->toBe([
        'type' => DataType::Object->value,
    ]);
