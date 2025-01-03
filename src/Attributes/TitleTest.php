<?php

use BasilLangevin\LaravelDataSchemas\Attributes\Title;

it('can be instantiated')
    ->expect(new Title('This is a title'))
    ->toBeInstanceOf(Title::class);

it('can get its value')
    ->expect(new Title('This is a title'))
    ->getTitle()
    ->toBe('This is a title');

/**
 * In order for the __toString() method to be counted in the coverage report,
 * we have to test it in long form.
 */
it('can be converted to a string', function () {
    expect((string) new Title('This is a title'))
        ->toBe('This is a title');
});
