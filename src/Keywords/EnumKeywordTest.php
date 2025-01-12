<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\EnumKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Accepted;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Declined;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Data;

covers(EnumKeyword::class);

enum KeywordTestEnum
{
    case First;
    case Second;
    case Third;
}

enum KeywordTestStringEnum: string
{
    case First = 'first';
    case Second = 'second';
    case Third = 'third';
}

enum KeywordTestIntegerEnum: int
{
    case First = 1;
    case Second = 2;
    case Third = 3;
}

describe('Keyword instantiation', function () {
    $basicOutput = collect([
        'type' => DataType::String->value,
    ]);

    $basicIntegerOutput = collect([
        'type' => DataType::Integer->value,
    ]);

    it('can set its enum to a backed enum')
        ->expect(StringSchema::make()->enum(KeywordTestStringEnum::class))
        ->getEnum()->toBe(KeywordTestStringEnum::class);

    it('can set its enum to an integer enum')
        ->expect(IntegerSchema::make()->enum(KeywordTestIntegerEnum::class))
        ->getEnum()->toBe(KeywordTestIntegerEnum::class);

    it('cannot set its enum to a non-backed enum', function () use ($basicOutput) {
        StringSchema::make()->enum(KeywordTestEnum::class)->applyKeyword(EnumKeyword::class, $basicOutput);
    })
        ->throws(\InvalidArgumentException::class, "Enum 'KeywordTestEnum' is not a backed enum. Only backed enums are supported.");

    it('cannot set its enum to a non-enum', function () use ($basicOutput) {
        StringSchema::make()->enum('KeywordTest')->applyKeyword(EnumKeyword::class, $basicOutput);
    })
        ->throws(\InvalidArgumentException::class, "Enum 'KeywordTest' is not a valid enum.");

    it('can set its enum to an array of values')
        ->expect(StringSchema::make()->enum([KeywordTestStringEnum::First, KeywordTestStringEnum::Second]))
        ->getEnum()->toBe([KeywordTestStringEnum::First, KeywordTestStringEnum::Second]);

    it('can get its enum')
        ->expect(StringSchema::make()->enum(KeywordTestStringEnum::class))
        ->getEnum()->toBe(KeywordTestStringEnum::class);

    it('can apply a backed enum to a schema')
        ->expect(StringSchema::make()->enum(KeywordTestStringEnum::class))
        ->applyKeyword(EnumKeyword::class, $basicOutput)
        ->toEqual(collect([
            'type' => DataType::String->value,
            'enum' => [KeywordTestStringEnum::First->value, KeywordTestStringEnum::Second->value, KeywordTestStringEnum::Third->value],
        ]));

    it('can adds value descriptions when applying an integer enum to a schema')
        ->expect(IntegerSchema::make()->enum(KeywordTestIntegerEnum::class))
        ->applyKeyword(EnumKeyword::class, $basicIntegerOutput)
        ->toEqual(collect([
            'type' => DataType::Integer->value,
            'enum' => [KeywordTestIntegerEnum::First->value, KeywordTestIntegerEnum::Second->value, KeywordTestIntegerEnum::Third->value],
            'x-enum-values' => ['First' => KeywordTestIntegerEnum::First->value, 'Second' => KeywordTestIntegerEnum::Second->value, 'Third' => KeywordTestIntegerEnum::Third->value],
        ]));

    it('can apply an array of values to a schema')
        ->expect(StringSchema::make()->enum([KeywordTestStringEnum::Second, 'Third', 4]))
        ->applyKeyword(EnumKeyword::class, $basicOutput)
        ->toEqual(collect([
            'type' => DataType::String->value,
            'enum' => [KeywordTestStringEnum::Second->value, 'Third', 4],
        ]));

    it('cannot apply non-backed enum values to a schema', function () use ($basicOutput) {
        StringSchema::make()
            ->enum([KeywordTestEnum::First, KeywordTestEnum::Second])
            ->applyKeyword(EnumKeyword::class, $basicOutput);
    })
        ->throws(\Exception::class, 'Non-backed enum values are not supported.');
});

describe('Property annotations', function () {
    it('it is set to the enum in the property type definition', function () {
        class EnumPropertyTypeKeywordKeywordTest extends Data
        {
            public function __construct(
                public KeywordTestStringEnum $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EnumPropertyTypeKeywordKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.type'))
            ->toBe(DataType::String->value);

        expect(Arr::get($schema, 'properties.testParameter.enum'))
            ->toBe([KeywordTestStringEnum::First->value, KeywordTestStringEnum::Second->value, KeywordTestStringEnum::Third->value]);

        expect(Arr::has($schema, 'properties.testParameter.x-enum-values'))
            ->toBeFalse();
    });

    it('it is set to the enum in the property type definition when the enum is an integer', function () {
        class EnumPropertyTypeIntegerKeywordKeywordTest extends Data
        {
            public function __construct(
                public KeywordTestIntegerEnum $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EnumPropertyTypeIntegerKeywordKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.type'))
            ->toBe(DataType::Integer->value);

        expect(Arr::get($schema, 'properties.testParameter.enum'))
            ->toBe([KeywordTestIntegerEnum::First->value, KeywordTestIntegerEnum::Second->value, KeywordTestIntegerEnum::Third->value]);

        expect(Arr::get($schema, 'properties.testParameter.x-enum-values'))
            ->toBe([
                'First' => KeywordTestIntegerEnum::First->value,
                'Second' => KeywordTestIntegerEnum::Second->value,
                'Third' => KeywordTestIntegerEnum::Third->value,
            ]);
    });

    it('is set to the enum in the Enum property attribute', function () {
        class EnumPropertyAttributeEnumKeywordKeywordTest extends Data
        {
            public function __construct(
                #[Enum(KeywordTestStringEnum::class)]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EnumPropertyAttributeEnumKeywordKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.enum'))
            ->toBe([KeywordTestStringEnum::First->value, KeywordTestStringEnum::Second->value, KeywordTestStringEnum::Third->value]);
    });

    it('only includes a single enum keyword when both the type and Attribute are present', function () {
        class EnumPropertyTypeAndAttributeEnumKeywordKeywordTest extends Data
        {
            public function __construct(
                #[Enum(KeywordTestStringEnum::class)]
                public KeywordTestStringEnum $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EnumPropertyTypeAndAttributeEnumKeywordKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.enum'))
            ->toBe([KeywordTestStringEnum::First->value, KeywordTestStringEnum::Second->value, KeywordTestStringEnum::Third->value]);
    });

    it('is set to the values of the In attribute when the property has the In attribute', function () {
        class EnumPropertyInAttributeEnumKeywordKeywordTest extends Data
        {
            public function __construct(
                #[In([1, 2, 3])]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EnumPropertyInAttributeEnumKeywordKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.enum'))
            ->toBe([1, 2, 3]);
    });

    it('is set to the values of the In attribute for a string property', function () {
        class EnumPropertyInAttributeStringEnumKeywordKeywordTest extends Data
        {
            public function __construct(
                #[In(['first', 'second', 'third'])]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EnumPropertyInAttributeStringEnumKeywordKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.enum'))
            ->toBe(['first', 'second', 'third']);
    });

    it('is set to the appropriate value when a string property has the Accepted attribute', function () {
        class EnumPropertyAcceptedAttributeEnumKeywordKeywordTest extends Data
        {
            public function __construct(
                #[Accepted]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EnumPropertyAcceptedAttributeEnumKeywordKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.enum'))
            ->toBe(['yes', 'on', '1', 'true']);
    });

    it('is not set when an int property has the Accepted attribute', function () {
        class EnumPropertyAcceptedAttributeIntegerEnumKeywordKeywordTest extends Data
        {
            public function __construct(
                #[Accepted]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EnumPropertyAcceptedAttributeIntegerEnumKeywordKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.enum'))
            ->toBeFalse();
    });

    it('is set to the appropriate value when a boolean property has the BooleanType attribute', function () {
        class EnumPropertyBooleanTypeAttributeEnumKeywordKeywordTest extends Data
        {
            public function __construct(
                #[BooleanType]
                public bool $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EnumPropertyBooleanTypeAttributeEnumKeywordKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.enum'))
            ->toBe([true, false]);
    });

    it('is set to the appropriate value when an integer property has the BooleanType attribute', function () {
        class EnumPropertyBooleanTypeAttributeIntegerEnumKeywordKeywordTest extends Data
        {
            public function __construct(
                #[BooleanType]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EnumPropertyBooleanTypeAttributeIntegerEnumKeywordKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.enum'))
            ->toBe([0, 1]);
    });

    it('is set to the appropriate value when a string property has the BooleanType attribute', function () {
        class EnumPropertyBooleanTypeAttributeStringEnumKeywordKeywordTest extends Data
        {
            public function __construct(
                #[BooleanType]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EnumPropertyBooleanTypeAttributeStringEnumKeywordKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.enum'))
            ->toBe(['0', '1']);
    });

    it('is set to the appropriate value when a string property has the Declined attribute', function () {
        class EnumPropertyDeclinedAttributeEnumKeywordKeywordTest extends Data
        {
            public function __construct(
                #[Declined]
                public string $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EnumPropertyDeclinedAttributeEnumKeywordKeywordTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.enum'))
            ->toBe(['no', 'off', '0', 'false']);
    });

    it('is not set when an integer property has the Declined attribute', function () {
        class EnumPropertyDeclinedAttributeIntegerEnumKeywordKeywordTest extends Data
        {
            public function __construct(
                #[Declined]
                public int $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(EnumPropertyDeclinedAttributeIntegerEnumKeywordKeywordTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.enum'))
            ->toBeFalse();
    });
});
