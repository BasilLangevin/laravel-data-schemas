<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\GreaterThanOrEqualTo;
use Spatie\LaravelData\Data;

it('sets the minLength keyword when applied to a string property', function () {
    class GreaterThanOrEqualToValidationRuleAttributeStringTest extends Data
    {
        public function __construct(
            #[GreaterThanOrEqualTo(3)]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(GreaterThanOrEqualToValidationRuleAttributeStringTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.minLength'))->toBe(3);
});

it('sets the minimum keyword when applied to an integer property', function () {
    class GreaterThanOrEqualToValidationRuleAttributeIntegerTest extends Data
    {
        public function __construct(
            #[GreaterThanOrEqualTo(3)]
            public int $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(GreaterThanOrEqualToValidationRuleAttributeIntegerTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Integer->value);
    expect(Arr::get($schema, 'properties.testParameter.minimum'))->toBe(3);
});

it('sets the minimum keyword when applied to a float property', function () {
    class GreaterThanOrEqualToValidationRuleAttributeFloatTest extends Data
    {
        public function __construct(
            #[GreaterThanOrEqualTo(3)]
            public float $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(GreaterThanOrEqualToValidationRuleAttributeFloatTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Number->value);
    expect(Arr::get($schema, 'properties.testParameter.minimum'))->toBe(3);
});
