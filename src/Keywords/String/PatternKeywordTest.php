<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\String\PatternKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;

covers(PatternKeyword::class);

$basicOutput = collect([
    'type' => DataType::String->value,
]);

it('can set its pattern')
    ->expect(StringSchema::make()->pattern('/^[a-z]+$/'))
    ->getPattern()->toBe('/^[a-z]+$/');

it('can get its pattern')
    ->expect(StringSchema::make()->pattern('/^[a-z]+$/'))
    ->getPattern()->toBe('/^[a-z]+$/');

it('can apply the pattern to a schema')
    ->expect(StringSchema::make()->pattern('/^[a-z]+$/'))
    ->applyKeyword(PatternKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'pattern' => '/^[a-z]+$/',
    ]));

it('combines multiple patterns into an allOf')
    ->todo()
    ->expect(StringSchema::make()->pattern('/^[a-z]+$/')->pattern('/^[0-9]+$/'))
    ->applyKeyword(PatternKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'allOf' => [
            ['pattern' => '/^[a-z]+$/'],
            ['pattern' => '/^[0-9]+$/'],
        ],
    ]));
