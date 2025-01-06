<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Data;

it('sets the pattern keyword when applied to a string property', function () {
    class IntegerTypePropertyAttributeTest extends Data
    {
        public function __construct(
            #[IntegerType]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(IntegerTypePropertyAttributeTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.pattern'))->toBe('/^[0-9]+$/');
});
