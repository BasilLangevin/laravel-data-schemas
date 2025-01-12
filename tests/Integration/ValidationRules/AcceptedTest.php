<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Accepted;
use Spatie\LaravelData\Data;

it('sets the enum keyword when applied to a string property', function () {
    class AcceptedPropertyAttributeTest extends Data
    {
        public function __construct(
            #[Accepted]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(AcceptedPropertyAttributeTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.enum'))->toBe([
        'yes',
        'on',
        '1',
        'true',
    ]);
});
