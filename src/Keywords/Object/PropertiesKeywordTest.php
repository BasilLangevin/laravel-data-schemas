<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Object\PropertiesKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;

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

$additionalProperties = [
    NumberSchema::make('test2'),
    BooleanSchema::make('test3'),
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

it('combines the properties of each instance when multiple instances are set')
    ->expect(PropertiesKeywordTestSchema::make()
        ->properties($properties)
        ->properties($additionalProperties)
    )
    ->applyKeyword(PropertiesKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Object->value,
        'properties' => [
            'test' => $properties[0]->toArray(),
            'test2' => $additionalProperties[0]->toArray(),
            'test3' => $additionalProperties[1]->toArray(),
        ],
    ]));
