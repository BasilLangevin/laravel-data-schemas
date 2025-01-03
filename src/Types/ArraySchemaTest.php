<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Types\ArraySchema;

covers(ArraySchema::class);

it('creates a array schema', function () {
    $schema = new ArraySchema;

    expect($schema->toArray())->toBe([
        'type' => DataType::Array->value,
    ]);
});
