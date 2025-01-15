<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Object\MaxPropertiesKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;

covers(MaxPropertiesKeyword::class);

$basicOutput = collect([
    'type' => DataType::Object->value,
]);

it('can set its maximum value')
    ->expect(ObjectSchema::make()->maxProperties(42))
    ->getMaxProperties()->toBe(42);

it('can get its maximum value')
    ->expect(ObjectSchema::make()->maxProperties(42))
    ->getMaxProperties()->toBe(42);

it('can apply the maximum value to a schema')
    ->expect(ObjectSchema::make()->maxProperties(42))
    ->applyKeyword(MaxPropertiesKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Object->value,
        'maxProperties' => 42,
    ]));

it('chooses the lowest value when multiple instances are set')
    ->expect(ObjectSchema::make()->maxProperties(42)->maxProperties(43))
    ->applyKeyword(MaxPropertiesKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Object->value,
        'maxProperties' => 42,
    ]));
