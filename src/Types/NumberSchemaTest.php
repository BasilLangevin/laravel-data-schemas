<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Types\NumberSchema;

covers(NumberSchema::class);

it('creates a number schema', function () {
    $schema = new NumberSchema;

    expect($schema->toArray())->toBe([
        'type' => DataType::Number->value,
    ]);
});
