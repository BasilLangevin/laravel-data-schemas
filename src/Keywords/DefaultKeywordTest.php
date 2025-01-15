<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\DefaultKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;

covers(DefaultKeyword::class);

$basicOutput = collect([
    'type' => DataType::Boolean->value,
]);

it('can set its default value')
    ->expect(BooleanSchema::make()->default(true))
    ->getDefault()->toBe(true);

it('can apply the default value to a schema')
    ->expect(BooleanSchema::make()->default(true))
    ->applyKeyword(DefaultKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Boolean->value,
        'default' => true,
    ]));
