<?php

use BasilLangevin\LaravelDataSchemas\Attributes\Description;

it('can be instantiated', function () {
    $description = new Description('This is a description');

    expect($description)->toBeInstanceOf(Description::class);
});

it('can get its value', function () {
    $description = new Description('This is a description');

    expect($description->getDescription())->toBe('This is a description');
});

it('can be converted to a string', function () {
    $description = new Description('This is a description');

    expect((string) $description)->toBe('This is a description');
});
