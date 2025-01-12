<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\Object\RequiredKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Present;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

covers(RequiredKeyword::class);

describe('Keyword instantiation', function () {
    class RequiredKeywordTestSchema extends Schema
    {
        public static array $keywords = [
            RequiredKeyword::class,
        ];
    }

    $basicOutput = collect([
        'type' => DataType::Object->value,
    ]);

    it('can set its required fields')
        ->expect(RequiredKeywordTestSchema::make())
        ->required(['test3', 'test4'])
        ->getRequired()->toBe(['test3', 'test4']);

    it('can apply the required fields to a schema')
        ->expect(RequiredKeywordTestSchema::make())
        ->required(['test3', 'test4'])
        ->applyKeyword(RequiredKeyword::class, $basicOutput)
        ->toEqual(collect([
            'type' => DataType::Object->value,
            'required' => ['test3', 'test4'],
        ]));
});

describe('Property annotations', function () {
    it('is set to the names of the required properties', function () {
        class RequiredPropertyTest extends Data
        {
            public function __construct(
                public bool $testParameter,
                public bool $testParameter2,
            ) {}
        }

        $schema = JsonSchema::make(RequiredPropertyTest::class)->toArray();

        expect(Arr::get($schema, 'required'))
            ->toBe(['testParameter', 'testParameter2']);
    });

    it('is not set when no properties are required', function () {
        class NoRequiredPropertiesTest extends Data
        {
            public function __construct(
                public ?bool $testParameter,
                public ?bool $testParameter2,
            ) {}
        }

        $schema = JsonSchema::make(NoRequiredPropertiesTest::class)->toArray();

        expect(Arr::has($schema, 'required'))
            ->toBeFalse();
    });

    it('includes properties with the Required attribute', function () {
        class RequiredPropertyAttributeTest extends Data
        {
            public function __construct(
                #[Required]
                public ?bool $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(RequiredPropertyAttributeTest::class)->toArray();

        expect(Arr::get($schema, 'required'))
            ->toBe(['testParameter']);
    });

    it('includes properties with the Present attribute', function () {
        class PresentPropertyAttributeTest extends Data
        {
            public function __construct(
                #[Present]
                public ?bool $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(PresentPropertyAttributeTest::class)->toArray();

        expect(Arr::get($schema, 'required'))
            ->toBe(['testParameter']);
    });

    test('a non-nullable property with an attribute is only included in the required array once', function () {
        class NonNullablePropertyWithAttributeTest extends Data
        {
            public function __construct(
                #[Required]
                public bool $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(NonNullablePropertyWithAttributeTest::class)->toArray();

        expect(Arr::get($schema, 'required'))
            ->toBe(['testParameter']);
    });

    test('a property with both the Required and Present attributes is only included in the required array once', function () {
        class PropertyWithBothAttributesTest extends Data
        {
            public function __construct(
                #[Required, Present]
                public bool $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(PropertyWithBothAttributesTest::class)->toArray();

        expect(Arr::get($schema, 'required'))
            ->toBe(['testParameter']);
    });
});
