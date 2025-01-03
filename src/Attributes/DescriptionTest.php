<?php

use BasilLangevin\LaravelDataSchemas\Attributes\Description;

it('can be instantiated')
    ->expect(new Description('This is a description'))
    ->toBeInstanceOf(Description::class);

it('can get its value')
    ->expect(new Description('This is a description'))
    ->getDescription()
    ->toBe('This is a description');

/**
 * In order for the __toString() and __construct() methods to be counted
 * in the coverage report, we have to test it in long form.
 */
it('can be converted to a string', function () {
    expect((string) new Description('This is a description'))
        ->toBe('This is a description');
});
