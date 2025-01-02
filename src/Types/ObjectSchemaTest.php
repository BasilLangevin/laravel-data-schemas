<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Types\ObjectSchema;

covers(ObjectSchema::class);

it('creates an object schema', function () {
    $schema = new ObjectSchema;

    expect($schema->toArray())->toBe([
        'type' => DataType::Object->value,
    ]);
});
