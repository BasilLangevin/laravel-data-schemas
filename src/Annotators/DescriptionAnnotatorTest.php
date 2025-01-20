<?php

use BasilLangevin\LaravelDataSchemas\Annotators\DescriptionAnnotator;
use BasilLangevin\LaravelDataSchemas\Attributes\Description;
use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

covers(DescriptionAnnotator::class);

uses(TestsSchemaTransformation::class);

it('can set its description with the Description attribute')
    ->expect(fn () => $this->class->addStringProperty('test', [Description::class => 'This is a test description.']))
    ->toHaveSchema('test', [
        'type' => DataType::String->value,
        'description' => 'This is a test description.',
    ]);

it('can set its description with the param annotation on the property', function () {
    class ParamPropertyAnnotationTest extends Data
    {
        public function __construct(
            /** @param bool $testParameter This is a test description. */
            public bool $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(ParamPropertyAnnotationTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.description'))
        ->toBe('This is a test description.');
});

it('can set its description with the var annotation on the property', function () {
    class VarPropertyAnnotationTest extends Data
    {
        public function __construct(
            /** @var bool $testParameter This is a test description. */
            public bool $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(VarPropertyAnnotationTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.description'))
        ->toBe('This is a test description.');
});

it('can set its description with a nameless var annotation on the property', function () {
    class NamelessVarAnnotationTest extends Data
    {
        public function __construct(
            /** @var bool This is a test description. */
            public bool $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(NamelessVarAnnotationTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.description'))
        ->toBe('This is a test description.');
});

it('can set its description with a DocBlock summary on the property', function () {
    class DocBlockSummaryPropertyTest extends Data
    {
        public function __construct(
            /** The property we're testing. */
            public bool $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(DocBlockSummaryPropertyTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.description'))
        ->toBe('The property we\'re testing.');
});

it('can set its description with a DocBlock description on the property', function () {
    class DocBlockDescriptionPropertyTest extends Data
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

    $schema = JsonSchema::make(DocBlockDescriptionPropertyTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.description'))
        ->toBe('It also has a description.');
});

it('can set its description with a DocBlock summary on the class', function () {
    /** The class we're testing. */
    class DocBlockSummaryClassTest extends Data
    {
        public function __construct(
            public bool $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(DocBlockSummaryClassTest::class)->toArray();

    expect(Arr::get($schema, 'description'))
        ->toBe('The class we\'re testing.');
});

it('can set its description with a DocBlock description on the class', function () {
    /**
     * The class we're testing.
     *
     * It also has a description.
     */
    class DocBlockDescriptionClassTest extends Data
    {
        public function __construct(
            public bool $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(DocBlockDescriptionClassTest::class)->toArray();

    expect(Arr::get($schema, 'description'))
        ->toBe('It also has a description.');
});

it('can set its description with a param annotation on the constructor', function () {
    class ParamConstructorAnnotationTest extends Data
    {
        /** @param bool $testParameter This is a test description. */
        public function __construct(
            public bool $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(ParamConstructorAnnotationTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.description'))
        ->toBe('This is a test description.');
});

it('can set its description with a var annotation on the class', function () {
    /** @var bool $testParameter This is a test description. */
    class VarClassAnnotationTest extends Data
    {
        public function __construct(
            public bool $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(VarClassAnnotationTest::class)->toArray();

    expect(Arr::get($schema, 'properties.testParameter.description'))
        ->toBe('This is a test description.');
});

it('does not set its description if no description is found', function () {
    class NoDescriptionTest extends Data
    {
        public function __construct(
            public bool $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(NoDescriptionTest::class)->toArray();

    expect(Arr::has($schema, 'properties.testParameter.description'))
        ->toBeFalse();
});

it('returns null if it has no description or constructor method', function () {
    class NoDescriptionOrConstructorMethodTest extends Data
    {
        public bool $testParameter;
    }

    $schema = JsonSchema::make(NoDescriptionOrConstructorMethodTest::class)->toArray();

    expect(Arr::has($schema, 'properties.testParameter.description'))
        ->toBeFalse();
});
