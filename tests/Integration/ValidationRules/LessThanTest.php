<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\LessThan;
use Spatie\LaravelData\Data;

it('sets the maxLength keyword when applied to a string property', function () {
    class LessThanValidationRuleAttributeStringTest extends Data
    {
        public function __construct(
            #[LessThan(3)]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(LessThanValidationRuleAttributeStringTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.maxLength'))->toBe(2);
});

it('sets the exclusiveMaximum keyword when applied to an integer property', function () {
    class LessThanValidationRuleAttributeIntegerTest extends Data
    {
        public function __construct(
            #[LessThan(3)]
            public int $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(LessThanValidationRuleAttributeIntegerTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Integer->value);
    expect(Arr::get($schema, 'properties.testParameter.exclusiveMaximum'))->toBe(3);
});

it('sets the exclusiveMaximum keyword when applied to a float property', function () {
    class LessThanValidationRuleAttributeFloatTest extends Data
    {
        public function __construct(
            #[LessThan(3)]
            public float $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(LessThanValidationRuleAttributeFloatTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Number->value);
    expect(Arr::get($schema, 'properties.testParameter.exclusiveMaximum'))->toBe(3);
});
