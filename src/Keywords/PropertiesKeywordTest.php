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

it('can set its required fields', function () {
    $properties = [
        BooleanSchema::make('test'),
        BooleanSchema::make('test2'),
    ];

    $schema = new PropertiesKeywordTestSchema;
    $schema->properties($properties);

    expect($schema->getProperties())->toBe($properties);
});

it('can apply the required fields to a schema', function () {
    $properties = [
        BooleanSchema::make('test1'),
        BooleanSchema::make('test2'),
    ];

    $schema = new PropertiesKeywordTestSchema;
    $schema->properties($properties);

    $data = collect([
        'type' => DataType::Object->value,
    ]);

    $result = $schema->applyKeyword(PropertiesKeyword::class, $data);

    expect($result)->toEqual(collect([
        'type' => DataType::Object->value,
        'properties' => [
            'test1' => $properties[0]->toArray(),
            'test2' => $properties[1]->toArray(),
        ],
    ]));
});
