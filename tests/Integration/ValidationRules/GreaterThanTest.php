<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\GreaterThan;
use Spatie\LaravelData\Data;

it('sets the exclusiveMinimum keyword when applied to an integer property', function () {
    class GreaterThanValidationRuleAttributeIntegerTest extends Data
    {
        public function __construct(
            #[GreaterThan(3)]
            public int $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(GreaterThanValidationRuleAttributeIntegerTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Integer->value);
    expect(Arr::get($schema, 'properties.testParameter.exclusiveMinimum'))->toBe(3);
});

it('sets the exclusiveMinimum keyword when applied to a float property', function () {
    class GreaterThanValidationRuleAttributeFloatTest extends Data
    {
        public function __construct(
            #[GreaterThan(3)]
            public float $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(GreaterThanValidationRuleAttributeFloatTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Number->value);
    expect(Arr::get($schema, 'properties.testParameter.exclusiveMinimum'))->toBe(3);
});
