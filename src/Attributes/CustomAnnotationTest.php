<?php

use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;

covers(CustomAnnotation::class);

it('can be instantiated')
    ->expect(fn () => new CustomAnnotation('annotation', 'value'))
    ->toBeInstanceOf(CustomAnnotation::class);

it('can be instantiated with an array')
    ->expect(new CustomAnnotation(['annotation' => 'value']))
    ->toBeInstanceOf(CustomAnnotation::class);

it('can get its value')
    ->expect(new CustomAnnotation('annotation', 'value'))
    ->getValue()
    ->toBe(['annotation' => 'value']);

it('can get its value with an array')
    ->expect(new CustomAnnotation(['annotation' => 'value']))
    ->getValue()
    ->toBe(['annotation' => 'value']);
