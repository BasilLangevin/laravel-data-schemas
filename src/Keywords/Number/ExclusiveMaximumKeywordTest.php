<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\ExclusiveMaximumKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;

covers(ExclusiveMaximumKeyword::class);

$basicOutput = collect([
    'type' => DataType::Number->value,
]);

it('can set its exclusive minimum value')
    ->expect(NumberSchema::make()->exclusiveMaximum(42))
    ->getExclusiveMaximum()->toBe(42);

it('can get its exclusive minimum value')
    ->expect(NumberSchema::make()->exclusiveMaximum(42))
    ->getExclusiveMaximum()->toBe(42);

it('can apply the exclusive minimum value to a schema')
    ->expect(NumberSchema::make()->exclusiveMaximum(42))
    ->applyKeyword(ExclusiveMaximumKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Number->value,
        'exclusiveMaximum' => 42,
    ]));

it('chooses the lowest value when multiple instances are set')
    ->expect(NumberSchema::make()->exclusiveMaximum(42)->exclusiveMaximum(43))
    ->applyKeyword(ExclusiveMaximumKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Number->value,
        'exclusiveMaximum' => 42,
    ]));
