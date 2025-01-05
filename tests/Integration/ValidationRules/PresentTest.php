<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Present;
use Spatie\LaravelData\Data;

it('sets the required keyword when applied to a property', function () {
    class PresentValidationRuleAttributeIntegerTest extends Data
    {
        public function __construct(
            #[Present]
            public ?int $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(PresentValidationRuleAttributeIntegerTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Integer->value);
    expect(Arr::get($schema, 'required'))->toBe(['testParameter']);
});
