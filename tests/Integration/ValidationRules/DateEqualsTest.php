<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Enums\Format;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\DateEquals;
use Spatie\LaravelData\Data;

todo('sets the format keyword when applied to a string property', function () {
    class DateEqualsPropertyAttributeTest extends Data
    {
        public function __construct(
            #[DateEquals('2025-01-01')]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(DateEqualsPropertyAttributeTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.format'))->toBe(Format::DateTime->value);
});
