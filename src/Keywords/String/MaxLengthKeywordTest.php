<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\String\MaxLengthKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;

covers(MaxLengthKeyword::class);

$basicOutput = collect([
    'type' => DataType::String->value,
]);

it('can set its maximum value')
    ->expect(fn () => StringSchema::make()->maxLength(42))
    ->getMaxLength()->toBe(42);

it('can get its maximum value')
    ->expect(StringSchema::make()->maxLength(42))
    ->getMaxLength()->toBe(42);

it('can apply the maximum value to a schema')
    ->expect(StringSchema::make()->maxLength(42))
    ->applyKeyword(MaxLengthKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'maxLength' => 42,
    ]));

it('chooses the lowest value when multiple instances are set')
    ->expect(StringSchema::make()->maxLength(42)->maxLength(43))
    ->applyKeyword(MaxLengthKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'maxLength' => 42,
    ]));
