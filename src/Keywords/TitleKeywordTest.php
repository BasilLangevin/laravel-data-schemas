<?php

use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\TitleKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

covers(TitleKeyword::class);

describe('Keyword instantiation', function () {
    class TitleKeywordTestSchema extends Schema
    {
        public static array $keywords = [
            TitleKeyword::class,
        ];
    }

    $basicOutput = collect([
        'type' => DataType::String->value,
    ]);

    it('can set its title')
        ->expect(TitleKeywordTestSchema::make()->title('test title'))
        ->getTitle()->toBe('test title');

    it('can apply the title to a schema')
        ->expect(TitleKeywordTestSchema::make())
        ->title('test title')
        ->applyKeyword(TitleKeyword::class, $basicOutput)
        ->toEqual(collect([
            'type' => DataType::String->value,
            'title' => 'test title',
        ]));
});

describe('Property annotations', function () {
    it('can be set with the Title attribute', function () {
        class TitleAttributeTest extends Data
        {
            public function __construct(
                #[Title('This is a test title.')]
                public bool $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(TitleAttributeTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.title'))
            ->toBe('This is a test title.');
    });

    it('can be set with a DocBlock summary on the property when the doc block has a description', function () {
        class DocBlockPropertySummaryWithDescriptionPropertyTest extends Data
        {
            public function __construct(
                /**
                 * The property we're testing.
                 *
                 * It also has a description.
                 */
                public bool $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DocBlockPropertySummaryWithDescriptionPropertyTest::class)->toArray();

        expect(Arr::get($schema, 'properties.testParameter.title'))
            ->toBe('The property we\'re testing.');
    });

    it('is not set with a DocBlock summary on the property when the doc block has no description', function () {
        class DocBlockPropertySummaryNoDescriptionTest extends Data
        {
            public function __construct(
                /** The property we're testing. */
                public bool $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DocBlockPropertySummaryNoDescriptionTest::class)->toArray();

        expect(Arr::has($schema, 'properties.testParameter.title'))
            ->toBeFalse();
    });

    it('can be set with a DocBlock summary on the class when the doc block has a description', function () {
        /**
         * The class we're testing.
         *
         * It also has a description.
         */
        class DocBlockClassSummaryWithDescriptionTest extends Data
        {
            public function __construct(
                public bool $testParameter,
            ) {}
        }

        $schema = JsonSchema::make(DocBlockClassSummaryWithDescriptionTest::class)->toArray();

        expect(Arr::get($schema, 'title'))
            ->toBe('The class we\'re testing.');
    });

    it('is not set with a DocBlock summary on the class when the doc block has no description', function () {
        /** The class we're testing. */
        class DocBlockClassSummaryNoDescriptionTest extends Data
        {
            public function __construct(public bool $testParameter) {}
        }

        $schema = JsonSchema::make(DocBlockClassSummaryNoDescriptionTest::class)->toArray();

        expect(Arr::has($schema, 'title'))
            ->toBeFalse();
    });
});
