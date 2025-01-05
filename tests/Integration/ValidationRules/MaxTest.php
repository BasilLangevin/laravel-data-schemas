<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

it('sets the maximum keyword when applied to an integer property', function () {
    class MaxValidationRuleAttributeIntegerTest extends Data
    {
        public function __construct(
            #[Max(3)]
            public int $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(MaxValidationRuleAttributeIntegerTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Integer->value);
    expect(Arr::get($schema, 'properties.testParameter.maximum'))->toBe(3);
});

it('sets the maximum keyword when applied to a float property', function () {
    class MaxValidationRuleAttributeFloatTest extends Data
    {
        public function __construct(
            #[Max(3)]
            public float $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(MaxValidationRuleAttributeFloatTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Number->value);
    expect(Arr::get($schema, 'properties.testParameter.maximum'))->toBe(3);
});
