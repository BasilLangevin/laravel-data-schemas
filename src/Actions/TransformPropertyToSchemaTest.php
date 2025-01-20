<?php

use BasilLangevin\LaravelDataSchemas\Actions\ApplyAnnotationsToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyEnumToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyRuleConfigurationsToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyTypeToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\TransformPropertyToSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Enums\TestIntegerEnum;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Enums\TestStringEnum;
use Spatie\LaravelData\Data;

covers(TransformPropertyToSchema::class);

class PropertyTransformActionTest extends Data
{
    public function __construct(
        public array $arrayProperty,
        public bool $boolProperty,
        public float $floatProperty,
        public int $intProperty,
        public object $objectProperty,
        public string $stringProperty,
        public TestStringEnum $stringEnumProperty,
        public TestIntegerEnum $intEnumProperty,
    ) {}
}

it('creates the correct Schema type from a Data class property', function ($property, $schemaType) {
    $schema = TransformPropertyToSchema::run(PropertyWrapper::make(PropertyTransformActionTest::class, $property));

    expect($schema)->toBeInstanceOf($schemaType);
    expect($schema->getName())->toBe($property);
})->with([
    ['arrayProperty', ArraySchema::class],
    ['boolProperty', BooleanSchema::class],
    ['floatProperty', NumberSchema::class],
    ['intProperty', IntegerSchema::class],
    ['objectProperty', ObjectSchema::class],
    ['stringProperty', StringSchema::class],
    ['stringEnumProperty', StringSchema::class],
    ['intEnumProperty', IntegerSchema::class],
]);

it('calls the ApplyTypeToSchema action', function () {
    $property = PropertyWrapper::make(PropertyTransformActionTest::class, 'stringProperty');

    $mock = $this->mock(ApplyTypeToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(StringSchema::make('stringProperty'));

    $action = new TransformPropertyToSchema;
    $action->handle($property);
});

it('calls the ApplyEnumToSchema action', function () {
    $property = PropertyWrapper::make(PropertyTransformActionTest::class, 'stringEnumProperty');

    $mock = $this->mock(ApplyEnumToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(StringSchema::make('stringEnumProperty'));

    $action = new TransformPropertyToSchema;
    $action->handle($property);
});

it('calls the ApplyAnnotationsToSchema action', function () {
    $property = PropertyWrapper::make(PropertyTransformActionTest::class, 'stringProperty');

    $mock = $this->mock(ApplyAnnotationsToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(StringSchema::make('stringProperty'));

    $action = new TransformPropertyToSchema;
    $action->handle($property);
});

it('calls the ApplyRuleConfigurationsToSchema action', function () {
    $property = PropertyWrapper::make(PropertyTransformActionTest::class, 'stringProperty');

    $mock = $this->mock(ApplyRuleConfigurationsToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(StringSchema::make('stringProperty'));

    $action = new TransformPropertyToSchema;
    $action->handle($property);
});
