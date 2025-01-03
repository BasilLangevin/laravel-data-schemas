<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Types\IntegerSchema;

covers(IntegerSchema::class);

it('creates a integer schema', function () {
    $schema = new IntegerSchema;

    expect($schema->toArray())->toBe([
        'type' => DataType::Integer->value,
    ]);
});
