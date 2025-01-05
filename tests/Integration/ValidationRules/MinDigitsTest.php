<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\MinDigits;
use Spatie\LaravelData\Data;

it('sets the minimum keyword when applied to an integer property', function () {
    class MinDigitsValidationRuleAttributeIntegerTest extends Data
    {
        public function __construct(
            #[MinDigits(3)]
            public int $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(MinDigitsValidationRuleAttributeIntegerTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Integer->value);
    expect(Arr::get($schema, 'properties.testParameter.minimum'))->toBe(100);
});
