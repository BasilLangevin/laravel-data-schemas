<?php

use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

it('sets the default value of the property set outside of the constructor', function () {
    class DefaultPropertyValueOutsideConstructorTest extends Data
    {
        public ?bool $testParameter = true;
    }

    $schema = JsonSchema::make(DefaultPropertyValueOutsideConstructorTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.default'))
        ->toBe(true);
});

it('sets the default value of the property set in the constructor', function () {
    class DefaultPropertyValueTest extends Data
    {
        public function __construct(
            public ?bool $testParameter = true,
        ) {}
    }

    $schema = JsonSchema::make(DefaultPropertyValueTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.default'))
        ->toBe(true);
});

it('still sets the default value when the default property value is null', function () {
    class DefaultPropertyValueNullTest extends Data
    {
        public function __construct(
            public ?bool $testParameter = null,
        ) {}
    }

    $schema = JsonSchema::make(DefaultPropertyValueNullTest::class)->toArray();

    expect(Arr::has($schema, 'properties.testParameter.default'))
        ->toBeTrue();
    expect(Arr::get($schema, 'properties.testParameter.default'))
        ->toBeNull();
});

it('sets the default value to the value of the constant if the property is set to a self::constant', function () {
    class DefaultPropertyValueConstantTest extends Data
    {
        public const TEST_CONSTANT = true;

        public function __construct(
            public bool $testParameter = self::TEST_CONSTANT,
        ) {}
    }

    $schema = JsonSchema::make(DefaultPropertyValueConstantTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.default'))
        ->toBe(true);
});

it('sets the default value to the value of the constant if the property is set to a class::constant', function () {
    class DefaultPropertyValueClassConstantTest extends Data
    {
        public const TEST_CONSTANT = true;

        public function __construct(
            public bool $testParameter = DefaultPropertyValueClassConstantTest::TEST_CONSTANT,
        ) {}
    }

    $schema = JsonSchema::make(DefaultPropertyValueClassConstantTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.default'))
        ->toBe(true);
});

it('does not set the default value when the property is not optional', function () {
    class DefaultPropertyValueNotOptionalTest extends Data
    {
        public function __construct(
            public bool $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(DefaultPropertyValueNotOptionalTest::class)->toArray();

    expect(Arr::has($schema, 'properties.testParameter.default'))
        ->toBeFalse();
});

it('does not set the default value when the property is not optional and the constructor is not defined', function () {
    class DefaultPropertyValueNotOptionalWithoutConstructorTest extends Data
    {
        public bool $testParameter;
    }

    $schema = JsonSchema::make(DefaultPropertyValueNotOptionalWithoutConstructorTest::class)->toArray();

    expect(Arr::has($schema, 'properties.testParameter.default'))
        ->toBeFalse();
});
