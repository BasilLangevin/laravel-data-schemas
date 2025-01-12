<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\MultipleOfKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\MultipleOf;
use Spatie\LaravelData\Data;

covers(MultipleOfKeyword::class);

describe('Keyword instantiation', function () {
    $basicOutput = collect([
        'type' => DataType::Number->value,
    ]);

    it('can set its multiple of value')
        ->expect(NumberSchema::make()->multipleOf(42))
        ->getMultipleOf()->toBe(42);

    it('can get its exclusive minimum value')
        ->expect(NumberSchema::make()->multipleOf(42))
        ->getMultipleOf()->toBe(42);

    it('can apply the multiple of value to a schema')
        ->expect(NumberSchema::make()->multipleOf(42))
        ->applyKeyword(MultipleOfKeyword::class, $basicOutput)
        ->toEqual(collect([
            'type' => DataType::Number->value,
            'multipleOf' => 42,
        ]));
});

describe('Property annotations', function () {
    it('is set to the value of the MultipleOf attribute', function () {
        class MultipleOfPropertyAttributeMultipleOfKeywordTest extends Data
        {
            public function __construct(
                #[MultipleOf(26)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(MultipleOfPropertyAttributeMultipleOfKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.multipleOf'))
            ->toBe(26);
    });

    it('is set to the value of the MultipleOf attribute when the value is a float', function () {
        class MultipleOfParameterAttributeMultipleOfKeywordTest extends Data
        {
            public function __construct(
                #[MultipleOf(26.5)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(MultipleOfParameterAttributeMultipleOfKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.multipleOf'))
            ->toBe(26.5);
    });
});
