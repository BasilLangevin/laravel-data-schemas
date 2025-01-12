<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\MaximumKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Attributes\Validation\LessThanOrEqualTo;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\MaxDigits;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Data;

covers(MaximumKeyword::class);

describe('Keyword instantiation', function () {
    $basicOutput = collect([
        'type' => DataType::Integer->value,
    ]);

    it('can set its maximum value')
        ->expect(IntegerSchema::make()->maximum(42))
        ->getMaximum()->toBe(42);

    it('can get its maximum value')
        ->expect(IntegerSchema::make()->maximum(42))
        ->getMaximum()->toBe(42);

    it('can apply the maximum value to a schema')
        ->expect(IntegerSchema::make()->maximum(42))
        ->applyKeyword(MaximumKeyword::class, $basicOutput)
        ->toEqual(collect([
            'type' => DataType::Integer->value,
            'maximum' => 42,
        ]));
});

describe('Property annotations', function () {
    it('is set to the upper bound of the Between attribute', function () {
        class BetweenPropertyAttributeMaximumKeywordTest extends Data
        {
            public function __construct(
                #[Between(37, 100)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(BetweenPropertyAttributeMaximumKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.maximum'))
            ->toBe(100);
    });

    it('is set to the value of the LessThanOrEqualTo attribute', function () {
        class LessThanOrEqualToPropertyAttributeMaximumKeywordTest extends Data
        {
            public function __construct(
                #[LessThanOrEqualTo(26)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(LessThanOrEqualToPropertyAttributeMaximumKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.maximum'))
            ->toBe(26);
    });

    it('is not set when LessThanOrEqualTo is non-numeric', function () {
        class LessThanOrEqualToNonNumericMaximumKeywordTest extends Data
        {
            public function __construct(
                #[LessThanOrEqualTo('otherParameter')]
                public int $testParameter,
                public int $otherParameter,
            ) {}
        }

        $schema = JsonSchema::make(LessThanOrEqualToNonNumericMaximumKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.maximum'))->toBeFalse();
    });

    it('is set to the value of the Max attribute', function () {
        class MaxPropertyAttributeMaximumKeywordTest extends Data
        {
            public function __construct(
                #[Max(68)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(MaxPropertyAttributeMaximumKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.maximum'))
            ->toBe(68);
    });

    it('is set to an appropriate value based on the MaxDigits attribute', function (int $digits, int $expected) {
        $name = 'MaxDigitsPropertyAttributeMaximumKeywordTest'.$digits;

        eval("class $name extends Spatie\LaravelData\Data
        {
            public function __construct(
                #[Spatie\LaravelData\Attributes\Validation\MaxDigits($digits)]
                public int \$testParameter,
            ) {}
        }");

        $schema = JsonSchema::make($name)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.maximum'))
            ->toBe($expected);
    })
        ->with([
            [2, 99],
            [3, 999],
            [4, 9999],
        ]);

    it('is set to the value of the Size attribute', function () {
        class SizePropertyAttributeMaximumKeywordTest extends Data
        {
            public function __construct(
                #[Size(4)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(SizePropertyAttributeMaximumKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.maximum'))
            ->toBe(4);
    });

    it('is set to the lowest maximum value from the attributes', function () {
        class MultiplePropertyAttributeMaximumKeywordTest extends Data
        {
            public function __construct(
                #[Size(4), Max(68), MaxDigits(2), Between(37, 100), LessThanOrEqualTo(26)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(MultiplePropertyAttributeMaximumKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.maximum'))
            ->toBe(4);
    });
});
