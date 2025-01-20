<?php

use BasilLangevin\LaravelDataSchemas\Actions\ApplyAnnotationsToSchema;
use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataSchemas\Attributes\Description;
use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Data;

covers(ApplyAnnotationsToSchema::class);

uses(TestsSchemaTransformation::class);

it('can apply the title annotation to a schema', function () {
    $this->class->addStringProperty('property', [Title::class => 'title']);
    $property = $this->class->getClassProperty('property');

    $schema = new StringSchema;

    $action = new ApplyAnnotationsToSchema;
    $result = $action->handle($schema, $property);

    expect($result->getTitle())->toBe('title');
});

it('can apply the description annotation to a schema', function () {
    $this->class->addStringProperty('property', [Description::class => 'description']);
    $property = $this->class->getClassProperty('property');

    $schema = new StringSchema;

    $action = new ApplyAnnotationsToSchema;
    $result = $action->handle($schema, $property);

    expect($result->getDescription())->toBe('description');
});

it('can apply the custom annotation to a schema', function () {
    $this->class->addStringProperty('property', [CustomAnnotation::class => ['key', 'value']]);
    $property = $this->class->getClassProperty('property');

    $schema = new StringSchema;

    $action = new ApplyAnnotationsToSchema;
    $result = $action->handle($schema, $property);

    expect($result->getCustomAnnotation())->toBe(['x-key' => 'value']);
});

it('can apply multiple custom annotations to a schema', function () {
    class TestMultipleCustomAnnotationsClass extends Data
    {
        public function __construct(
            #[CustomAnnotation('foo', 'bar')]
            #[CustomAnnotation('baz', 'qux')]
            public string $test,
        ) {}
    }

    $property = PropertyWrapper::make(TestMultipleCustomAnnotationsClass::class, 'test');

    $schema = new StringSchema;

    $action = new ApplyAnnotationsToSchema;
    $result = $action->handle($schema, $property);

    expect($result->getCustomAnnotation())->toBe([
        'x-foo' => 'bar',
        'x-baz' => 'qux',
    ]);
});

it('can apply the default annotation to a schema', function () {
    $this->class->addStringProperty('property', [], 'default');
    $property = $this->class->getClassProperty('property');

    $schema = new StringSchema;

    $action = new ApplyAnnotationsToSchema;
    $result = $action->handle($schema, $property);

    expect($result->getDefault())->toBe('default');
});
