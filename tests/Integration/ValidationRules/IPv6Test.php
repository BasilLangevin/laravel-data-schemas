<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Enums\Format;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\IPv6;
use Spatie\LaravelData\Data;

it('sets the format keyword when applied to a string property', function () {
    class IPv6PropertyAttributeTest extends Data
    {
        public function __construct(
            #[IPv6]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(IPv6PropertyAttributeTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.format'))->toBe(Format::IPv6->value);
});
