<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Types\BooleanSchema;

covers(BooleanSchema::class);

it('creates a boolean schema', function () {
    $schema = new BooleanSchema;

    expect($schema->toArray())->toBe([
        'type' => DataType::Boolean->value,
    ]);
});
