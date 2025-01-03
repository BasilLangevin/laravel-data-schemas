<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Types\StringSchema;

covers(StringSchema::class);

it('creates a string schema', function () {
    $schema = new StringSchema;

    expect($schema->toArray())->toBe([
        'type' => DataType::String->value,
    ]);
});
