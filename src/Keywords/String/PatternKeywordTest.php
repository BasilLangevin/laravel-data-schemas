<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\String\PatternKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Alpha;
use Spatie\LaravelData\Attributes\Validation\AlphaDash;
use Spatie\LaravelData\Attributes\Validation\AlphaNumeric;
use Spatie\LaravelData\Attributes\Validation\DoesntEndWith;
use Spatie\LaravelData\Attributes\Validation\DoesntStartWith;
use Spatie\LaravelData\Attributes\Validation\EndsWith;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Lowercase;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\StartsWith;
use Spatie\LaravelData\Attributes\Validation\Uppercase;
use Spatie\LaravelData\Data;

covers(PatternKeyword::class);

describe('Keyword instantiation', function () {
    $basicOutput = collect([
        'type' => DataType::String->value,
    ]);

    it('can set its pattern')
        ->expect(StringSchema::make()->pattern('/^[a-z]+$/'))
        ->getPattern()->toBe('/^[a-z]+$/');

    it('can get its pattern')
        ->expect(StringSchema::make()->pattern('/^[a-z]+$/'))
        ->getPattern()->toBe('/^[a-z]+$/');

    it('can apply the pattern to a schema')
        ->expect(StringSchema::make()->pattern('/^[a-z]+$/'))
        ->applyKeyword(PatternKeyword::class, $basicOutput)
        ->toEqual(collect([
            'type' => DataType::String->value,
            'pattern' => '/^[a-z]+$/',
        ]));
});

describe('Property annotations', function () {
    it('is set to the Alpha pattern when the Alpha attribute is present', function () {
        class AlphaPropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[Alpha]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(AlphaPropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/^[a-zA-Z]+$/');
    });

    it('is set to the AlphaDash pattern when the AlphaDash attribute is present', function () {
        class AlphaDashPropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[AlphaDash]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(AlphaDashPropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/^[a-zA-Z0-9_-]+$/');
    });

    it('is set to the AlphaNumeric pattern when the AlphaNumeric attribute is present', function () {
        class AlphaNumericPropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[AlphaNumeric]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(AlphaNumericPropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/^[a-zA-Z0-9]+$/');
    });

    it('is set to the DoesntEndWith pattern when the DoesntEndWith attribute is present', function () {
        class DoesntEndWithPropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[DoesntEndWith('a')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DoesntEndWithPropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/^(?!.*(a)$).*$/');
    });

    it('joins multiple DoesntEndWith values into a single regex pattern', function () {
        class DoesntEndWithMultiplePropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[DoesntEndWith('a', 'b', '$20.00')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DoesntEndWithMultiplePropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/^(?!.*(a|b|\$20\.00)$).*$/');
    });

    it('is set to the DoesntStartWith pattern when the DoesntStartWith attribute is present', function () {
        class DoesntStartWithPropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[DoesntStartWith('a')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DoesntStartWithPropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/^(?!a).*$/');
    });

    it('joins multiple DoesntStartWith values into a single regex pattern', function () {
        class DoesntStartWithMultiplePropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[DoesntStartWith('25%', 'apple', '\App\Models\User')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DoesntStartWithMultiplePropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/^(?!25%|apple|\\\App\\\Models\\\User).*$/');
    });

    it('is set to the EndsWith pattern when the EndsWith attribute is present', function () {
        class EndsWithPropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[EndsWith('a')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EndsWithPropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/(a)$/');
    });

    it('joins multiple EndsWith values into a single regex pattern', function () {
        class EndsWithMultiplePropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[EndsWith('a', 'b', '$20.00')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EndsWithMultiplePropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/(a|b|\$20\.00)$/');
    });

    it('is set to the IntegerType pattern when the IntegerType attribute is present', function () {
        class IntegerTypePropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[IntegerType]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(IntegerTypePropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/^[0-9]+$/');
    });

    it('is set to the Lowercase pattern when the Lowercase attribute is present', function () {
        class LowercasePropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[Lowercase]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(LowercasePropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/^[^A-Z]+$/');
    });

    it('is set to the Numeric pattern when the Numeric attribute is present', function () {
        class NumericPropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[Numeric]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(NumericPropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/^-?(\d+|\d*\.\d+)([eE][+-]?\d+)?$/');
    });

    it('is set to the Regex pattern when the Regex attribute is present', function () {
        class RegexPropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[Regex('/^[a-z]+$/')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(RegexPropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/^[a-z]+$/');
    });

    it('is set to the StartsWith pattern when the StartsWith attribute is present', function () {
        class StartsWithPropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[StartsWith('a')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(StartsWithPropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/^(a)/');
    });

    it('joins multiple StartsWith values into a single regex pattern', function () {
        class StartsWithMultiplePropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[StartsWith('a', 'b', '$20.00')]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(StartsWithMultiplePropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/^(a|b|\$20\.00)/');
    });

    it('is set to the Uppercase pattern when the Uppercase attribute is present', function () {
        class UppercasePropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[Uppercase]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(UppercasePropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.pattern'))
            ->toBe('/^[^a-z]+$/');
    });

    it('joins multiple patterns into an allOf schema when multiple attributes are present', function () {
        class MultiplePatternsPropertyAttributePatternKeywordTest extends Data
        {
            public function __construct(
                #[Alpha, Uppercase]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(MultiplePatternsPropertyAttributePatternKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.allOf'))
            ->toBe([
                [
                    'pattern' => '/^[a-zA-Z]+$/',
                ],
                [
                    'pattern' => '/^[^a-z]+$/',
                ],
            ]);
    })->todo();
});
