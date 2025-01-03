<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\RequiredKeyword;
use BasilLangevin\LaravelDataSchemas\Types\Schema;

covers(RequiredKeyword::class);

class RequiredKeywordTestSchema extends Schema
{
    public static array $keywords = [
        RequiredKeyword::class,
    ];
}

$basicOutput = collect([
    'type' => DataType::Object->value,
]);

it('can set its required fields')
    ->expect(RequiredKeywordTestSchema::make())
    ->required(['test3', 'test4'])
    ->getRequired()->toBe(['test3', 'test4']);

it('can apply the required fields to a schema')
    ->expect(RequiredKeywordTestSchema::make())
    ->required(['test3', 'test4'])
    ->applyKeyword(RequiredKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Object->value,
        'required' => ['test3', 'test4'],
    ]));
