<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\General\TypeKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Object\RequiredKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;

covers(RequiredKeyword::class);

class RequiredKeywordTestSchema extends ObjectSchema
{
    public static array $keywords = [
        TypeKeyword::class,
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

it('combines the required fields of each instance when multiple instances are set')
    ->expect(RequiredKeywordTestSchema::make()
        ->required(['test1', 'test2'])
        ->required(['test2', 'test3'])
    )
    ->applyKeyword(RequiredKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Object->value,
        'required' => ['test1', 'test2', 'test3'],
    ]));
