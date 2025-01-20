<?php

use BasilLangevin\LaravelDataSchemas\Attributes\Title;

it('can be instantiated')
    ->expect(new Title('This is a title'))
    ->toBeInstanceOf(Title::class);

it('can get its value')
    ->expect(fn () => new Title('This is a title'))
    ->getValue()
    ->toBe('This is a title');
