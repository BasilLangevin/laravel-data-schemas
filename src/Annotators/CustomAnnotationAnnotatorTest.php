<?php

use BasilLangevin\LaravelDataSchemas\Annotators\CustomAnnotationAnnotator;
use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

covers(CustomAnnotationAnnotator::class);

uses(TestsSchemaTransformation::class);

it('does not apply custom annotations to a schema if the property is not annotated', function () {
    $this->class->addStringProperty('test');

    $schema = $this->class->getSchemaClass();

    expect(fn () => $schema->getProperties()[0]->getCustomAnnotation())
        ->toThrow(\Exception::class, 'The keyword "customAnnotation" has not been set.');

    expect(Arr::get($this->class->getSchema(), 'properties.test'))->toEqual([
        'type' => 'string',
    ]);
});

it('can apply a single custom annotation to a schema')
    ->expect(fn () => $this->class->addStringProperty(
        'test',
        [CustomAnnotation::class => ['foo', 'bar']]
    ))
    ->toHaveSchema('test', [
        'type' => 'string',
        'x-foo' => 'bar',
    ]);

it('can apply multiple custom annotations to a schema', function () {
    class TestMultipleCustomAnnotationsClass extends Data
    {
        public function __construct(
            #[CustomAnnotation('foo', 'bar')]
            #[CustomAnnotation('baz', 'qux')]
            public string $test,
        ) {}
    }

    $schema = JsonSchema::make(TestMultipleCustomAnnotationsClass::class);

    expect($schema->toArray())->toHaveKey('properties.test', [
        'type' => 'string',
        'x-foo' => 'bar',
        'x-baz' => 'qux',
    ]);
});
