<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Data;

it('sets the minLength and maxLength keywords when applied to a string property', function () {
    class SizeValidationRuleAttributeStringTest extends Data
    {
        public function __construct(
            #[Size(3)]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(SizeValidationRuleAttributeStringTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.minLength'))->toBe(3);
    expect(Arr::get($schema, 'properties.testParameter.maxLength'))->toBe(3);
});

it('sets the size keyword when applied to an integer property', function () {
    class SizeValidationRuleAttributeIntegerTest extends Data
    {
        public function __construct(
            #[Size(37)]
            public int $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(SizeValidationRuleAttributeIntegerTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Integer->value);
    expect(Arr::get($schema, 'properties.testParameter.minimum'))->toBe(37);
    expect(Arr::get($schema, 'properties.testParameter.maximum'))->toBe(37);
});

it('sets the size keyword when applied to a float property', function () {
    class SizeValidationRuleAttributeFloatTest extends Data
    {
        public function __construct(
            #[Size(37)]
            public float $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(SizeValidationRuleAttributeFloatTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Number->value);
    expect(Arr::get($schema, 'properties.testParameter.minimum'))->toBe(37);
    expect(Arr::get($schema, 'properties.testParameter.maximum'))->toBe(37);
});
