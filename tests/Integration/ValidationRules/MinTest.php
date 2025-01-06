<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

it('sets the minLength keyword when applied to a string property', function () {
    class MinValidationRuleAttributeStringTest extends Data
    {
        public function __construct(
            #[Min(3)]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(MinValidationRuleAttributeStringTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.minLength'))->toBe(3);
});

it('sets the minimum keyword when applied to an integer property', function () {
    class MinValidationRuleAttributeIntegerTest extends Data
    {
        public function __construct(
            #[Min(3)]
            public int $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(MinValidationRuleAttributeIntegerTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Integer->value);
    expect(Arr::get($schema, 'properties.testParameter.minimum'))->toBe(3);
});

it('sets the minimum keyword when applied to a float property', function () {
    class MinValidationRuleAttributeFloatTest extends Data
    {
        public function __construct(
            #[Min(3)]
            public float $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(MinValidationRuleAttributeFloatTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Number->value);
    expect(Arr::get($schema, 'properties.testParameter.minimum'))->toBe(3);
});
