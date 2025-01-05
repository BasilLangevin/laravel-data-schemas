<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Data;

it('sets the maximum and minimum keywords when applied to an integer property', function () {
    class BetweenValidationRuleAttributeIntegerTest extends Data
    {
        public function __construct(
            #[Between(1, 10)]
            public int $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(BetweenValidationRuleAttributeIntegerTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Integer->value);
    expect(Arr::get($schema, 'properties.testParameter.minimum'))->toBe(1);
    expect(Arr::get($schema, 'properties.testParameter.maximum'))->toBe(10);
});

it('sets the maximum and minimum keywords when applied to a float property', function () {
    class BetweenValidationRuleAttributeFloatTest extends Data
    {
        public function __construct(
            #[Between(1, 10)]
            public float $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(BetweenValidationRuleAttributeFloatTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Number->value);
    expect(Arr::get($schema, 'properties.testParameter.minimum'))->toBe(1);
    expect(Arr::get($schema, 'properties.testParameter.maximum'))->toBe(10);
});
