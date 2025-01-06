<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\MinimumKeyword;
use BasilLangevin\LaravelDataSchemas\Types\NumberSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Attributes\Validation\GreaterThanOrEqualTo;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\MinDigits;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Data;

covers(MinimumKeyword::class);

describe('Keyword instantiation', function () {
    $basicOutput = collect([
        'type' => DataType::Number->value,
    ]);

    it('can set its minimum value')
        ->expect(NumberSchema::make()->minimum(42))
        ->getMinimum()->toBe(42);

    it('can get its minimum value')
        ->expect(NumberSchema::make()->minimum(42))
        ->getMinimum()->toBe(42);

    it('can apply the minimum value to a schema')
        ->expect(NumberSchema::make()->minimum(42))
        ->applyKeyword(MinimumKeyword::class, $basicOutput)
        ->toEqual(collect([
            'type' => DataType::Number->value,
            'minimum' => 42,
        ]));
});

describe('Property annotations', function () {
    it('is set to the lower bound of the Between attribute', function () {
        class BetweenPropertyAttributeMinimumKeywordTest extends Data
        {
            public function __construct(
                #[Between(37, 100)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(BetweenPropertyAttributeMinimumKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.minimum'))
            ->toBe(37);
    });

    it('is set to the value of the GreaterThanOrEqualTo attribute', function () {
        class GreaterThanOrEqualToPropertyAttributeMinimumKeywordTest extends Data
        {
            public function __construct(
                #[GreaterThanOrEqualTo(26)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(GreaterThanOrEqualToPropertyAttributeMinimumKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.minimum'))
            ->toBe(26);
    });

    it('is not set when GreaterThanOrEqualTo is non-numeric', function () {
        class GreaterThanOrEqualToNonNumericMinimumKeywordTest extends Data
        {
            public function __construct(
                #[GreaterThanOrEqualTo('otherParameter')]
                public int $testParameter,
                public int $otherParameter,
            ) {}
        }

        $schema = JsonSchema::make(GreaterThanOrEqualToNonNumericMinimumKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.minimum'))->toBeFalse();
    });

    it('is set to the value of the Min attribute', function () {
        class MinPropertyAttributeMinimumKeywordTest extends Data
        {
            public function __construct(
                #[Min(68)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(MinPropertyAttributeMinimumKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.minimum'))
            ->toBe(68);
    });

    it('is set to an appropriate value based on the MinDigits attribute', function (int $digits, int $expected) {
        $name = 'MinDigitsPropertyAttributeMinimumKeywordTest'.$digits;

        eval("class $name extends Spatie\LaravelData\Data
        {
            public function __construct(
                #[Spatie\LaravelData\Attributes\Validation\MinDigits($digits)]
                public int \$testParameter,
            ) {}
        }");

        $schema = JsonSchema::make($name)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.minimum'))
            ->toBe($expected);
    })
        ->with([
            [2, 10],
            [3, 100],
            [4, 1000],
        ]);

    it('is set to the value of the Size attribute', function () {
        class SizePropertyAttributeMinimumKeywordTest extends Data
        {
            public function __construct(
                #[Size(4)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(SizePropertyAttributeMinimumKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.minimum'))
            ->toBe(4);
    });

    it('is set to the highest minimum value from the attributes', function () {
        class MultiplePropertyAttributeMinimumKeywordTest extends Data
        {
            public function __construct(
                #[Size(4), Min(68), MinDigits(2), Between(37, 100), GreaterThanOrEqualTo(26)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(MultiplePropertyAttributeMinimumKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.minimum'))
            ->toBe(68);
    });
});
