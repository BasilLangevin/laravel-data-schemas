<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\String\MinLengthKeyword;
use BasilLangevin\LaravelDataSchemas\Types\StringSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Attributes\Validation\GreaterThan;
use Spatie\LaravelData\Attributes\Validation\GreaterThanOrEqualTo;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Data;

covers(MinLengthKeyword::class);

describe('Keyword instantiation', function () {
    $basicOutput = collect([
        'type' => DataType::String->value,
    ]);

    it('can set its minimum value')
        ->expect(StringSchema::make()->minLength(42))
        ->getMinLength()->toBe(42);

    it('can get its minimum value')
        ->expect(StringSchema::make()->minLength(42))
        ->getMinLength()->toBe(42);

    it('can apply the minimum value to a schema')
        ->expect(StringSchema::make()->minLength(42))
        ->applyKeyword(MinLengthKeyword::class, $basicOutput)
        ->toEqual(collect([
            'type' => DataType::String->value,
            'minLength' => 42,
        ]));
});

describe('Property annotations', function () {
    it('is set to the lower bound of the Between attribute', function () {
        class BetweenPropertyAttributeMinLengthKeywordTest extends Data
        {
            public function __construct(
                #[Between(37, 100)]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(BetweenPropertyAttributeMinLengthKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.minLength'))
            ->toBe(37);
    });

    it('is set to one plus the value of the GreaterThan attribute', function () {
        class GreaterThanPropertyAttributeMinLengthKeywordTest extends Data
        {
            public function __construct(
                #[GreaterThan(26)]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(GreaterThanPropertyAttributeMinLengthKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.minLength'))
            ->toBe(27);
    });

    it('is not set when GreaterThan is non-numeric', function () {
        class GreaterThanNonNumericPropertyAttributeMinLengthKeywordTest extends Data
        {
            public function __construct(
                #[GreaterThan('otherParameter')]
                public string $testParameter,
                public string $otherParameter,
            ) {}
        }

        $schema = JsonSchema::make(GreaterThanNonNumericPropertyAttributeMinLengthKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.minLength'))->toBeFalse();
    });

    it('is set to the value of the GreaterThanOrEqualTo attribute', function () {
        class GreaterThanOrEqualToPropertyAttributeMinLengthKeywordTest extends Data
        {
            public function __construct(
                #[GreaterThanOrEqualTo(26)]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(GreaterThanOrEqualToPropertyAttributeMinLengthKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.minLength'))
            ->toBe(26);
    });

    it('is not set when GreaterThanOrEqualTo is non-numeric', function () {
        class GreaterThanOrEqualToNonNumericMinLengthKeywordTest extends Data
        {
            public function __construct(
                #[GreaterThanOrEqualTo('otherParameter')]
                public string $testParameter,
                public string $otherParameter,
            ) {}
        }

        $schema = JsonSchema::make(GreaterThanOrEqualToNonNumericMinLengthKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.minLength'))->toBeFalse();
    });

    it('is set to the value of the Min attribute', function () {
        class MinPropertyAttributeMinLengthKeywordTest extends Data
        {
            public function __construct(
                #[Min(68)]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(MinPropertyAttributeMinLengthKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.minLength'))
            ->toBe(68);
    });

    it('is set to the value of the Size attribute', function () {
        class SizePropertyAttributeMinLengthKeywordTest extends Data
        {
            public function __construct(
                #[Size(4)]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(SizePropertyAttributeMinLengthKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.minLength'))
            ->toBe(4);
    });

    it('is set to the highest minimum value from the attributes', function () {
        class MultiplePropertyAttributeMinLengthKeywordTest extends Data
        {
            public function __construct(
                #[Size(4), Min(68), Between(37, 100), GreaterThan(26), GreaterThanOrEqualTo(26)]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(MultiplePropertyAttributeMinLengthKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.minLength'))
            ->toBe(68);
    });
});
