<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\ExclusiveMinimumKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;

covers(ExclusiveMinimumKeyword::class);

$basicOutput = collect([
    'type' => DataType::Number->value,
]);

it('can set its exclusive minimum value')
    ->expect(fn () => NumberSchema::make()->exclusiveMinimum(42))
    ->getExclusiveMinimum()->toBe(42);

it('can get its exclusive minimum value')
    ->expect(NumberSchema::make()->exclusiveMinimum(42))
    ->getExclusiveMinimum()->toBe(42);

it('can apply the exclusive minimum value to a schema')
    ->expect(NumberSchema::make()->exclusiveMinimum(42))
    ->applyKeyword(ExclusiveMinimumKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Number->value,
        'exclusiveMinimum' => 42,
    ]));

it('chooses the highest value when multiple instances are set')
    ->expect(NumberSchema::make()->exclusiveMinimum(43)->exclusiveMinimum(42))
    ->applyKeyword(ExclusiveMinimumKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Number->value,
        'exclusiveMinimum' => 43,
    ]));
