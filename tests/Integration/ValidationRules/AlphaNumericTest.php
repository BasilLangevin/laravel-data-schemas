<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\AlphaNumeric;
use Spatie\LaravelData\Data;

it('sets the pattern keyword when applied to a string property', function () {
    class AlphaNumericPropertyAttributeTest extends Data
    {
        public function __construct(
            #[AlphaNumeric]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(AlphaNumericPropertyAttributeTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.pattern'))->toBe('/^[a-zA-Z0-9]+$/');
});
