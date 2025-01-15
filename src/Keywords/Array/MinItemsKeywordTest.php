<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Array\MinItemsKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;

covers(MinItemsKeyword::class);

$basicOutput = collect([
    'type' => DataType::Array->value,
]);

it('can set its minimum value')
    ->expect(ArraySchema::make()->minItems(42))
    ->getMinItems()->toBe(42);

it('can get its minimum value')
    ->expect(ArraySchema::make()->minItems(42))
    ->getMinItems()->toBe(42);

it('can apply the minimum value to a schema')
    ->expect(ArraySchema::make()->minItems(42))
    ->applyKeyword(MinItemsKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Array->value,
        'minItems' => 42,
    ]));

it('chooses the highest value when multiple instances are set')
    ->expect(ArraySchema::make()->minItems(43)->minItems(42))
    ->applyKeyword(MinItemsKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Array->value,
        'minItems' => 43,
    ]));
