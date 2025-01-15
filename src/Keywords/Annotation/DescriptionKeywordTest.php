<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\General\TypeKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;

covers(DescriptionKeyword::class);

class DescriptionKeywordTestSchema extends StringSchema
{
    public static array $keywords = [
        TypeKeyword::class,
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
