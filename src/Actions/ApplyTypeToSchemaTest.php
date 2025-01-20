<?php

use BasilLangevin\LaravelDataSchemas\Actions\ApplyTypeToSchema;
use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;

covers(ApplyTypeToSchema::class);

uses(TestsSchemaTransformation::class);

it('can apply the type to the schema', function () {
    $schema = new StringSchema;

    $this->class->addStringProperty('test');
    $property = $this->class->getClassProperty('test');

    ApplyTypeToSchema::run($schema, $property);

    expect($schema->getType())->toBe(DataType::String);
});
