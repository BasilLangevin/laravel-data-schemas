<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Data;

enum ValidationRulesTestStringEnum: string
{
    case First = 'first';
    case Second = 'second';
    case Third = 'third';
}
enum ValidationRulesTestIntegerEnum: int
{
    case First = 1;
    case Second = 2;
    case Third = 3;
}

it('sets the enum keyword when applied to a string property', function () {
    class EnumStringPropertyAttributeValidationRulesTest extends Data
    {
        public function __construct(
            #[Enum(ValidationRulesTestStringEnum::class)]
            public string $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(EnumStringPropertyAttributeValidationRulesTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::String->value);
    expect(Arr::get($schema, 'properties.testParameter.enum'))->toBe([
        'first',
        'second',
        'third',
    ]);
    expect(Arr::has($schema, 'properties.testParameter.x-enum-values'))->toBeFalse();
});

it('sets the enum and x-enum-values keywords when applied to an integer property', function () {
    class EnumIntegerPropertyAttributeValidationRulesTest extends Data
    {
        public function __construct(
            #[Enum(ValidationRulesTestIntegerEnum::class)]
            public int $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(EnumIntegerPropertyAttributeValidationRulesTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.type'))->toBe(DataType::Integer->value);
    expect(Arr::get($schema, 'properties.testParameter.enum'))->toBe([1, 2, 3]);
    expect(Arr::get($schema, 'properties.testParameter.x-enum-values'))->toBe(['First' => 1, 'Second' => 2, 'Third' => 3]);
});
