<?php

use BasilLangevin\LaravelDataSchemas\Attributes\Description;

it('can be instantiated')
    ->expect(new Description('This is a description'))
    ->toBeInstanceOf(Description::class);

it('can get its value')
    ->expect(new Description('This is a description'))
    ->getDescription()
    ->toBe('This is a description');

it('can be converted to a string')
    ->expect((string) new Description('This is a description'))
    ->toBe('This is a description');
