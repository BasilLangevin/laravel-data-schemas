<?php

use BasilLangevin\LaravelDataSchemas\Actions\ApplyAnnotationsToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyRuleConfigurationsToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\TransformDataClassToSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use Spatie\LaravelData\Attributes\Validation\Present;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

covers(TransformDataClassToSchema::class);

class PropertylessDataClassTransformActionTest extends Data {}

class DataClassTransformActionTest extends Data
{
    public function __construct(
        public string $requiredString,
        public string $stringWithDefault,
        public int $requiredInt,
        public ?int $optionalInt = null,
        #[Present]
        public ?string $presentAttribute = null,
        #[Required]
        public ?string $requiredAttribute = 'optional',
    ) {}
}

it('creates an ObjectSchema from a Data class', function () {
    $schema = TransformDataClassToSchema::run(ClassWrapper::make(DataClassTransformActionTest::class));

    expect($schema)->toBeInstanceOf(ObjectSchema::class);
});

it('calls the ApplyAnnotationsToSchema action', function () {
    $class = ClassWrapper::make(PropertylessDataClassTransformActionTest::class);

    $mock = $this->mock(ApplyAnnotationsToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(ObjectSchema::make('Test'));

    $action = new TransformDataClassToSchema;
    $action->handle($class);
});

it('calls the ApplyRuleConfigurationsToSchema action', function () {
    $class = ClassWrapper::make(PropertylessDataClassTransformActionTest::class);

    $mock = $this->mock(ApplyRuleConfigurationsToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(ObjectSchema::make('Test'));

    $action = new TransformDataClassToSchema;
    $action->handle($class);
});

it('adds properties to the schema', function () {
    $class = ClassWrapper::make(DataClassTransformActionTest::class);

    $schema = TransformDataClassToSchema::run($class);

    expect($schema->getProperties())->toHaveCount(6);
    expect(collect($schema->getProperties())->map->getName()->toArray())->toBe([
        'requiredString',
        'stringWithDefault',
        'requiredInt',
        'optionalInt',
        'presentAttribute',
        'requiredAttribute',
    ]);
});

/**
 * PHP constructor properties are always required if they are not nullable.
 */
it('adds required properties to the schema', function () {
    $class = ClassWrapper::make(DataClassTransformActionTest::class);

    $schema = TransformDataClassToSchema::run($class);

    expect($schema->getRequired())->toBe([
        'requiredString',
        'stringWithDefault',
        'requiredInt',
        'presentAttribute',
        'requiredAttribute',
    ]);
});
