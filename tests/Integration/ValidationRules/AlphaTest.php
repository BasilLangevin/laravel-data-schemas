<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Alpha;
use Spatie\LaravelData\Data;

it('sets the pattern keyword when applied to a string property', function () {
    class AlphaPropertyAttributeTest extends Data
    {
        public function __construct(
            #[Alpha]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(AlphaPropertyAttributeTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.pattern'))->toBe('/^[a-zA-Z]+$/');
});
