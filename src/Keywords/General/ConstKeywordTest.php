<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\General\ConstKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;

covers(ConstKeyword::class);

$basicOutput = collect([
    'type' => DataType::Boolean->value,
]);

it('can set its const value')
    ->expect(BooleanSchema::make()->const(true))
    ->getConst()->toBe(true);

it('can apply the const value to a schema')
    ->expect(BooleanSchema::make()->const(true))
    ->applyKeyword(ConstKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Boolean->value,
        'const' => true,
    ]));
