<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\MinimumKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;

covers(MinimumKeyword::class);

$basicOutput = collect([
    'type' => DataType::Number->value,
]);

it('can set its minimum value')
    ->expect(fn () => NumberSchema::make()->minimum(42))
    ->getMinimum()->toBe(42);

it('can get its minimum value')
    ->expect(NumberSchema::make()->minimum(42))
    ->getMinimum()->toBe(42);

it('can apply the minimum value to a schema')
    ->expect(NumberSchema::make()->minimum(42))
    ->applyKeyword(MinimumKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Number->value,
        'minimum' => 42,
    ]));

it('chooses the highest value when multiple instances are set')
    ->expect(NumberSchema::make()->minimum(43)->minimum(42))
    ->applyKeyword(MinimumKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Number->value,
        'minimum' => 43,
    ]));
