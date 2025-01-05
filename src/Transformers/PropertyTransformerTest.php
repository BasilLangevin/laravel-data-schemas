<?php

use BasilLangevin\LaravelDataSchemas\Transformers\PropertyTransformer;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use BasilLangevin\LaravelDataSchemas\Types\BooleanSchema;

covers(PropertyTransformer::class);

class TestReflectionTransformerClass
{
    public bool $test;
}

it('can transform a property into a schema', function () {
    $schema = PropertyTransformer::transform(new ReflectionProperty(TestReflectionTransformerClass::class, 'test'));

    expect($schema)->toBeInstanceOf(BooleanSchema::class);
});

it('can transform a reflection helper into a schema', function () {
    $schema = PropertyTransformer::transform(new ReflectionHelper(new ReflectionProperty(TestReflectionTransformerClass::class, 'test')));

    expect($schema)->toBeInstanceOf(BooleanSchema::class);
});
