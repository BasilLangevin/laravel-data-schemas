<?php

use BasilLangevin\LaravelDataSchemas\Attributes\Description;

it('can be instantiated')
    ->expect(new Description('This is a description'))
    ->toBeInstanceOf(Description::class);

it('can get its value')
    ->expect(fn () => new Description('This is a description'))
    ->getValue()
    ->toBe('This is a description');
