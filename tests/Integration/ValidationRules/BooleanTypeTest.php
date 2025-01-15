<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Data;

it('sets the enum keyword when applied to a string property', function () {
    class BooleanTypePropertyAttributeTest extends Data
    {
        public function __construct(
            #[BooleanType]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(BooleanTypePropertyAttributeTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.enum'))->toBe([
        '0',
        '1',
    ]);
});

it('sets the enum keyword when applied to an integer property', function () {
    class BooleanTypeIntegerPropertyAttributeTest extends Data
    {
        public function __construct(
            #[BooleanType]
            public int $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(BooleanTypeIntegerPropertyAttributeTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Integer->value);
    expect(Arr::get($schema, 'properties.testParameter.enum'))->toBe([
        0,
        1,
    ]);
});

it('sets the enum keyword when applied to a boolean property', function () {
    class BooleanTypeBooleanPropertyAttributeTest extends Data
    {
        public function __construct(
            #[BooleanType]
            public bool $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(BooleanTypeBooleanPropertyAttributeTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Boolean->value);
    expect(Arr::get($schema, 'properties.testParameter.enum'))->toBe([
        false,
        true,
    ]);
});
