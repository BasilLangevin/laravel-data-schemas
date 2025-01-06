<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\MultipleOf;
use Spatie\LaravelData\Data;

it('sets the multipleOf keyword when applied to an integer property', function () {
    class MultipleOfValidationRuleAttributeIntegerTest extends Data
    {
        public function __construct(
            #[MultipleOf(26)]
            public int $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(MultipleOfValidationRuleAttributeIntegerTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Integer->value);
    expect(Arr::get($schema, 'properties.testParameter.multipleOf'))->toBe(26);
});

it('sets the multipleOf keyword when applied to a float property', function () {
    class MultipleOfValidationRuleAttributeFloatTest extends Data
    {
        public function __construct(
            #[MultipleOf(26.5)]
            public float $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(MultipleOfValidationRuleAttributeFloatTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Number->value);
    expect(Arr::get($schema, 'properties.testParameter.multipleOf'))->toBe(26.5);
});
