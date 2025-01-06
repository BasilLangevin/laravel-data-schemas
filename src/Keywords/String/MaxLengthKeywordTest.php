<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\String\MaxLengthKeyword;
use BasilLangevin\LaravelDataSchemas\Types\StringSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Attributes\Validation\LessThan;
use Spatie\LaravelData\Attributes\Validation\LessThanOrEqualTo;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Data;

covers(MaxLengthKeyword::class);

describe('Keyword instantiation', function () {
    $basicOutput = collect([
        'type' => DataType::String->value,
    ]);

    it('can set its maximum value')
        ->expect(StringSchema::make()->maxLength(42))
        ->getMaxLength()->toBe(42);

    it('can get its maximum value')
        ->expect(StringSchema::make()->maxLength(42))
        ->getMaxLength()->toBe(42);

    it('can apply the maximum value to a schema')
        ->expect(StringSchema::make()->maxLength(42))
        ->applyKeyword(MaxLengthKeyword::class, $basicOutput)
        ->toEqual(collect([
            'type' => DataType::String->value,
            'maxLength' => 42,
        ]));
});

describe('Property annotations', function () {
    it('is set to the higher bound of the Between attribute', function () {
        class BetweenPropertyAttributeMaxLengthKeywordTest extends Data
        {
            public function __construct(
                #[Between(37, 100)]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(BetweenPropertyAttributeMaxLengthKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.maxLength'))
            ->toBe(100);
    });

    it('is set to one less than the value of the LessThan attribute', function () {
        class LessThanPropertyAttributeMaxLengthKeywordTest extends Data
        {
            public function __construct(
                #[LessThan(26)]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(LessThanPropertyAttributeMaxLengthKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.maxLength'))
            ->toBe(25);
    });

    it('is not set when the LessThan attribute is non-numeric', function () {
        class LessThanNonNumericPropertyAttributeMaxLengthKeywordTest extends Data
        {
            public function __construct(
                #[LessThan('otherParameter')]
                public string $testParameter,
                public string $otherParameter,
            ) {}
        }

        $schema = JsonSchema::make(LessThanNonNumericPropertyAttributeMaxLengthKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.maxLength'))->toBeFalse();
    });

    it('is set to the value of the LessThanOrEqualTo attribute', function () {
        class LessThanOrEqualToPropertyAttributeMaxLengthKeywordTest extends Data
        {
            public function __construct(
                #[LessThanOrEqualTo(26)]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(LessThanOrEqualToPropertyAttributeMaxLengthKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.maxLength'))
            ->toBe(26);
    });

    it('is not set when LessThanOrEqualTo is non-numeric', function () {
        class LessThanOrEqualToNonNumericMaxLengthKeywordTest extends Data
        {
            public function __construct(
                #[LessThanOrEqualTo('otherParameter')]
                public string $testParameter,
                public string $otherParameter,
            ) {}
        }

        $schema = JsonSchema::make(LessThanOrEqualToNonNumericMaxLengthKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.maxLength'))->toBeFalse();
    });

    it('is set to the value of the Max attribute', function () {
        class MaxPropertyAttributeMaxLengthKeywordTest extends Data
        {
            public function __construct(
                #[Max(68)]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(MaxPropertyAttributeMaxLengthKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.maxLength'))
            ->toBe(68);
    });

    it('is set to the value of the Size attribute', function () {
        class SizePropertyAttributeMaxLengthKeywordTest extends Data
        {
            public function __construct(
                #[Size(4)]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(SizePropertyAttributeMaxLengthKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.maxLength'))
            ->toBe(4);
    });

    it('is set to the highest maximum value from the attributes', function () {
        class MultiplePropertyAttributeMaxLengthKeywordTest extends Data
        {
            public function __construct(
                #[Size(4), Max(68), Between(37, 42), LessThan(26), LessThanOrEqualTo(26)]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(MultiplePropertyAttributeMaxLengthKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.maxLength'))
            ->toBe(68);
    });
});
