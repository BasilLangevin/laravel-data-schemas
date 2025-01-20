<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\String\MinLengthKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;

covers(MinLengthKeyword::class);

$basicOutput = collect([
    'type' => DataType::String->value,
]);

it('can set its minimum value')
    ->expect(fn () => StringSchema::make()->minLength(42))
    ->getMinLength()->toBe(42);

it('can get its minimum value')
    ->expect(StringSchema::make()->minLength(42))
    ->getMinLength()->toBe(42);

it('can apply the minimum value to a schema')
    ->expect(StringSchema::make()->minLength(42))
    ->applyKeyword(MinLengthKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'minLength' => 42,
    ]));

it('chooses the highest value when multiple instances are set')
    ->expect(StringSchema::make()->minLength(43)->minLength(42))
    ->applyKeyword(MinLengthKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'minLength' => 43,
    ]));
