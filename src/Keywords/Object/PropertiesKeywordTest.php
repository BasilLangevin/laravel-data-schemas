<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\Object\PropertiesKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

covers(PropertiesKeyword::class);

describe('Keyword instantiation', function () {
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
});

describe('Property annotations', function () {
    it('is set to the data objects public properties', function () {
        class PublicDataObjectPropertiesTest extends Data
        {
            protected string $notIncluded;

            public function __construct(
                public bool $testParameter,
                public bool $testParameter2,
            ) {}
        }

        $schema = JsonSchema::make(PublicDataObjectPropertiesTest::class)->toArray();

        expect(Arr::get($schema, 'properties'))
            ->toBe([
                'testParameter' => BooleanSchema::make('testParameter')->toArray(),
                'testParameter2' => BooleanSchema::make('testParameter2')->toArray(),
            ]);
    });

    it('is not set when the data object has no public properties', function () {
        class PrivateDataObjectPropertiesTest extends Data
        {
            private function __construct() {}
        }

        $schema = JsonSchema::make(PrivateDataObjectPropertiesTest::class)->toArray();

        expect(Arr::has($schema, 'properties'))
            ->toBeFalse();
    });
});
