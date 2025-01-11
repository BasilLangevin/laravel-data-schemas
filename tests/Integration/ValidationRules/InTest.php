<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Data;

enum ValidationRulesTestStringIn: string
{
    case First = 'first';
    case Second = 'second';
    case Third = 'third';
}

enum ValidationRulesTestIntegerIn: int
{
    case First = 1;
    case Second = 2;
    case Third = 3;
}

it('sets the enum keyword when applied to a string property', function () {
    class InStringPropertyAttributeValidationRulesTest extends Data
    {
        public function __construct(
            #[In(['first', ValidationRulesTestStringIn::Second, 'third'])]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(InStringPropertyAttributeValidationRulesTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.enum'))->toBe([
        'first',
        'second',
        'third',
    ]);
    expect(Arr::has($schema, 'properties.testParameter.x-enum-values'))->toBeFalse();
});

it('sets the enum and x-enum-values keywords when applied to an integer property', function () {
    class InIntegerPropertyAttributeValidationRulesTest extends Data
    {
        public function __construct(
            #[In([1, ValidationRulesTestIntegerIn::Second, 3])]
            public int $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(InIntegerPropertyAttributeValidationRulesTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Integer->value);
    expect(Arr::get($schema, 'properties.testParameter.enum'))->toBe([1, 2, 3]);
});
