<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\General\TypeKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;

covers(TypeKeyword::class);

it('can set its type value')
    ->expect((new BooleanSchema)->type(DataType::Boolean->value))
    ->getType()->toBe(DataType::Boolean->value);

it('can set its type to a DataType enum value')
    ->expect((new BooleanSchema)->type(DataType::Boolean))
    ->getType()->toBe(DataType::Boolean);

it('its type is set by default when created with the make method')
    ->expect(BooleanSchema::make()->getType())
    ->toBe(DataType::Boolean);

it('can apply the type value to a schema')
    ->expect((new BooleanSchema)->type(DataType::Boolean->value))
    ->applyKeyword(TypeKeyword::class, collect())
    ->toEqual(collect([
        'type' => DataType::Boolean->value,
    ]));
