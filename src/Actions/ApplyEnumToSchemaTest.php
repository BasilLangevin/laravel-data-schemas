<?php

use BasilLangevin\LaravelDataSchemas\Actions\ApplyEnumToSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Enums\TestStringEnum;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;

covers(ApplyEnumToSchema::class);

uses(TestsSchemaTransformation::class);

it('applies the enum to the schema', function () {
    $this->class->addProperty(TestStringEnum::class, 'property');
    $property = $this->class->getClassProperty('property');

    $schema = new StringSchema;

    $action = new ApplyEnumToSchema;
    $result = $action->handle($schema, $property);

    expect($result->getEnum())->toBe(TestStringEnum::class);
});
