<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Decoration\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;

covers(DescriptionKeyword::class);

class DescriptionKeywordTestSchema extends Schema
{
    public static array $keywords = [
        DescriptionKeyword::class,
    ];
}

$basicOutput = collect([
    'type' => DataType::String->value,
]);

it('can set its description on construction')
    ->expect(DescriptionKeywordTestSchema::make('test', 'test description'))
    ->getDescription()->toBe('test description');

it('can set its description after construction')
    ->expect(DescriptionKeywordTestSchema::make('test'))
    ->description('test description')
    ->getDescription()->toBe('test description');

it('can apply the description to a schema')
    ->expect(DescriptionKeywordTestSchema::make())
    ->description('test description')
    ->applyKeyword(DescriptionKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'description' => 'test description',
    ]));
