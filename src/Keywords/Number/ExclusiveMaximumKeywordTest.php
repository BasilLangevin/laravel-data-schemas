<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\ExclusiveMaximumKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\LessThan;
use Spatie\LaravelData\Data;

covers(ExclusiveMaximumKeyword::class);

describe('Keyword instantiation', function () {
    $basicOutput = collect([
        'type' => DataType::Number->value,
    ]);

    it('can set its exclusive minimum value')
        ->expect(NumberSchema::make()->exclusiveMaximum(42))
        ->getExclusiveMaximum()->toBe(42);

    it('can get its exclusive minimum value')
        ->expect(NumberSchema::make()->exclusiveMaximum(42))
        ->getExclusiveMaximum()->toBe(42);

    it('can apply the exclusive minimum value to a schema')
        ->expect(NumberSchema::make()->exclusiveMaximum(42))
        ->applyKeyword(ExclusiveMaximumKeyword::class, $basicOutput)
        ->toEqual(collect([
            'type' => DataType::Number->value,
            'exclusiveMaximum' => 42,
        ]));
});

describe('Property annotations', function () {
    it('is set to the value of the LessThan attribute', function () {
        class LessThanPropertyAttributeExclusiveMaximumKeywordTest extends Data
        {
            public function __construct(
                #[LessThan(26)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(LessThanPropertyAttributeExclusiveMaximumKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.exclusiveMaximum'))
            ->toBe(26);
    });

    it('is not set when LessThan is non-numeric', function () {
        class LessThanNonNumericExclusiveMaximumKeywordTest extends Data
        {
            public function __construct(
                #[LessThan('otherParameter')]
                public int $testParameter,
                public int $otherParameter,
            ) {}
        }

        $schema = JsonSchema::make(LessThanNonNumericExclusiveMaximumKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.exclusiveMaximum'))->toBeFalse();
    });
});
