<?php

use BasilLangevin\LaravelDataSchemas\Types\BooleanSchema;

it('creates a boolean schema', function () {
    $schema = new BooleanSchema;

    expect($schema->toArray())->toBe([
        'type' => 'boolean',
    ]);
});
