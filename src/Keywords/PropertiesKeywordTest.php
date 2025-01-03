<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\PropertiesKeyword;
use BasilLangevin\LaravelDataSchemas\Types\BooleanSchema;
use BasilLangevin\LaravelDataSchemas\Types\Schema;

covers(PropertiesKeyword::class);

class PropertiesKeywordTestSchema extends Schema
{
    public static array $keywords = [
        PropertiesKeyword::class,
    ];
}

$properties = [
    BooleanSchema::make('test'),
    BooleanSchema::make('test2'),
];

$basicOutput = collect([
    'type' => DataType::Object->value,
]);

it('can set its properties')
    ->expect(PropertiesKeywordTestSchema::make())
    ->properties($properties)
    ->getProperties()->toBe($properties);

it('can apply the properties to a schema')
    ->expect(PropertiesKeywordTestSchema::make())
    ->properties($properties)
    ->applyKeyword(PropertiesKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Object->value,
        'properties' => [
            'test' => $properties[0]->toArray(),
            'test2' => $properties[1]->toArray(),
        ],
    ]));
