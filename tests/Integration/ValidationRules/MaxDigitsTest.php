<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\MaxDigits;
use Spatie\LaravelData\Data;

it('sets the maximum keyword when applied to an integer property', function () {
    class MaxDigitsValidationRuleAttributeIntegerTest extends Data
    {
        public function __construct(
            #[MaxDigits(3)]
            public int $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(MaxDigitsValidationRuleAttributeIntegerTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Integer->value);
    expect(Arr::get($schema, 'properties.testParameter.maximum'))->toBe(999);
});
