<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\LessThanOrEqualTo;
use Spatie\LaravelData\Data;

it('sets the maxLength keyword when applied to a string property', function () {
    class LessThanOrEqualToValidationRuleAttributeStringTest extends Data
    {
        public function __construct(
            #[LessThanOrEqualTo(3)]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(LessThanOrEqualToValidationRuleAttributeStringTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.maxLength'))->toBe(3);
});

it('sets the maximum keyword when applied to an integer property', function () {
    class LessThanOrEqualToValidationRuleAttributeIntegerTest extends Data
    {
        public function __construct(
            #[LessThanOrEqualTo(3)]
            public int $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(LessThanOrEqualToValidationRuleAttributeIntegerTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Integer->value);
    expect(Arr::get($schema, 'properties.testParameter.maximum'))->toBe(3);
});

it('sets the maximum keyword when applied to a float property', function () {
    class LessThanOrEqualToValidationRuleAttributeFloatTest extends Data
    {
        public function __construct(
            #[LessThanOrEqualTo(3)]
            public float $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(LessThanOrEqualToValidationRuleAttributeFloatTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Number->value);
    expect(Arr::get($schema, 'properties.testParameter.maximum'))->toBe(3);
});
