<?php

use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

$basicSchema = [
    'type' => 'object',
    'properties' => [
        'testParameter' => [
            'type' => 'boolean',
        ],
    ],
    'required' => [
        'testParameter',
    ],
];

it('can create a basic boolean schema from a data object', function () use ($basicSchema) {
    class BasicBooleanData extends Data
    {
        public function __construct(
            public bool $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(BasicBooleanData::class);

    expect($schema)->toBeInstanceOf(ObjectSchema::class);
    expect($schema->name())->toBe('BasicBooleanData');

    expect($schema->toArray())->toBe($basicSchema);
});

it('can create a boolean schema with a description from a data object', function () use ($basicSchema) {
    class BooleanWithDescriptionData extends Data
    {
        public function __construct(
            /** The test parameter. */
            public bool $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(BooleanWithDescriptionData::class);

    expect($schema)->toBeInstanceOf(ObjectSchema::class);
    expect($schema->name())->toBe('BooleanWithDescriptionData');

    Arr::set($basicSchema, 'properties.testParameter.description', 'The test parameter.');

    expect($schema->toArray())->toEqual($basicSchema);
});
