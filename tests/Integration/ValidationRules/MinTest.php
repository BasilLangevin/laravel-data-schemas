<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

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
