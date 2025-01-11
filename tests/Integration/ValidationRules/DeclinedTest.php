<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Declined;
use Spatie\LaravelData\Data;

it('sets the enum keyword when applied to a string property', function () {
    class DeclinedPropertyAttributeTest extends Data
    {
        public function __construct(
            #[Declined]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(DeclinedPropertyAttributeTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.enum'))->toBe([
        'no',
        'off',
        '0',
        'false',
    ]);
});
