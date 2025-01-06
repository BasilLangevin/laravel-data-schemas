<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\AlphaDash;
use Spatie\LaravelData\Data;

it('sets the pattern keyword when applied to a string property', function () {
    class AlphaDashPropertyAttributeTest extends Data
    {
        public function __construct(
            #[AlphaDash]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(AlphaDashPropertyAttributeTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.pattern'))->toBe('/^[a-zA-Z0-9_-]+$/');
});
