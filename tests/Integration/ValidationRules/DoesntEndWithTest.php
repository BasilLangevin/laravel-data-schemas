<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\DoesntEndWith;
use Spatie\LaravelData\Data;

it('sets the pattern keyword when applied to a string property', function () {
    class DoesntEndWithPropertyAttributeTest extends Data
    {
        public function __construct(
            #[DoesntEndWith('foo', 'bar')]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(DoesntEndWithPropertyAttributeTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.pattern'))->toBe('/^(?!.*(foo|bar)$).*$/');
});
