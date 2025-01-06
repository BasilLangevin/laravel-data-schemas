<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\Number\ExclusiveMinimumKeyword;
use BasilLangevin\LaravelDataSchemas\Types\NumberSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\GreaterThan;
use Spatie\LaravelData\Data;

covers(ExclusiveMinimumKeyword::class);

describe('Keyword instantiation', function () {
    $basicOutput = collect([
        'type' => DataType::Number->value,
    ]);

    it('can set its exclusive minimum value')
        ->expect(NumberSchema::make()->exclusiveMinimum(42))
        ->getExclusiveMinimum()->toBe(42);

    it('can get its exclusive minimum value')
        ->expect(NumberSchema::make()->exclusiveMinimum(42))
        ->getExclusiveMinimum()->toBe(42);

    it('can apply the exclusive minimum value to a schema')
        ->expect(NumberSchema::make()->exclusiveMinimum(42))
        ->applyKeyword(ExclusiveMinimumKeyword::class, $basicOutput)
        ->toEqual(collect([
            'type' => DataType::Number->value,
            'exclusiveMinimum' => 42,
        ]));
});

describe('Property annotations', function () {
    it('is set to the value of the GreaterThan attribute', function () {
        class GreaterThanPropertyAttributeExclusiveMinimumKeywordTest extends Data
        {
            public function __construct(
                #[GreaterThan(26)]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(GreaterThanPropertyAttributeExclusiveMinimumKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.exclusiveMinimum'))
            ->toBe(26);
    });

    it('is not set when GreaterThan is non-numeric', function () {
        class GreaterThanNonNumericExclusiveMinimumKeywordTest extends Data
        {
            public function __construct(
                #[GreaterThan('otherParameter')]
                public int $testParameter,
                public int $otherParameter,
            ) {}
        }

        $schema = JsonSchema::make(GreaterThanNonNumericExclusiveMinimumKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.exclusiveMinimum'))->toBeFalse();
    });
});
