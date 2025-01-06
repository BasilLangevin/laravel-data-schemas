<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Enums\Format;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Data;

it('sets the format keyword when applied to a string property', function () {
    class UrlPropertyAttributeTest extends Data
    {
        public function __construct(
            #[Url]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(UrlPropertyAttributeTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.format'))->toBe(Format::Uri->value);
});
