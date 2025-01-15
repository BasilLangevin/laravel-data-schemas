<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Object\MinPropertiesKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;

covers(MinPropertiesKeyword::class);

$basicOutput = collect([
    'type' => DataType::Object->value,
]);

it('can set its minimum value')
    ->expect(ObjectSchema::make()->minProperties(42))
    ->getMinProperties()->toBe(42);

it('can get its minimum value')
    ->expect(ObjectSchema::make()->minProperties(42))
    ->getMinProperties()->toBe(42);

it('can apply the minimum value to a schema')
    ->expect(ObjectSchema::make()->minProperties(42))
    ->applyKeyword(MinPropertiesKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Object->value,
        'minProperties' => 42,
    ]));

it('chooses the highest value when multiple instances are set')
    ->expect(ObjectSchema::make()->minProperties(43)->minProperties(42))
    ->applyKeyword(MinPropertiesKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Object->value,
        'minProperties' => 43,
    ]));
