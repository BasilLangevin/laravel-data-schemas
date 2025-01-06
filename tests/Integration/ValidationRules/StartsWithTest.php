<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\StartsWith;
use Spatie\LaravelData\Data;

it('sets the pattern keyword when applied to a string property', function () {
    class StartsWithPropertyAttributeTest extends Data
    {
        public function __construct(
            #[StartsWith('foo', 'bar')]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(StartsWithPropertyAttributeTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.pattern'))->toBe('/^(foo|bar)/');
});
