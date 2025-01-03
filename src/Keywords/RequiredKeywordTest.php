<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\RequiredKeyword;
use BasilLangevin\LaravelDataSchemas\Types\Schema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

covers(RequiredKeyword::class);

describe('Keyword instantiation', function () {
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
});

describe('Property annotations', function () {
    it('is set to the names of the required properties', function () {
        class RequiredPropertyTest extends Data
        {
            public function __construct(
                public bool $testParameter,
                public bool $testParameter2,
            ) {}
        }

        $schema = JsonSchema::make(RequiredPropertyTest::class)->toArray();

        expect(Arr::get($schema, 'required'))
            ->toBe(['testParameter', 'testParameter2']);
    });

    it('is not set when no properties are required', function () {
        class NoRequiredPropertiesTest extends Data
        {
            public function __construct(
                public ?bool $testParameter,
                public ?bool $testParameter2,
            ) {}
        }

        $schema = JsonSchema::make(NoRequiredPropertiesTest::class)->toArray();

        expect(Arr::has($schema, 'required'))
            ->toBeFalse();
    });
});
