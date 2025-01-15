<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\MaximumKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;

covers(MaximumKeyword::class);

$basicOutput = collect([
    'type' => DataType::Integer->value,
]);

it('can set its maximum value')
    ->expect(IntegerSchema::make()->maximum(42))
    ->getMaximum()->toBe(42);

it('can get its maximum value')
    ->expect(IntegerSchema::make()->maximum(42))
    ->getMaximum()->toBe(42);

it('can apply the maximum value to a schema')
    ->expect(IntegerSchema::make()->maximum(42))
    ->applyKeyword(MaximumKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Integer->value,
        'maximum' => 42,
    ]));

it('chooses the lowest value when multiple instances are set')
    ->expect(IntegerSchema::make()->maximum(42)->maximum(43))
    ->applyKeyword(MaximumKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Integer->value,
        'maximum' => 42,
    ]));
