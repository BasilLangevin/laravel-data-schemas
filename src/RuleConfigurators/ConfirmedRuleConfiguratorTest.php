<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\ConfirmedRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Confirmed;

covers(ConfirmedRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the x-matches annotation to a boolean schema')
    ->expect(fn () => $this->class
        ->addBooleanProperty('test', [Confirmed::class])
        ->addBooleanProperty('test_confirmed')
    )
    ->toHaveSchema('test', [
        'type' => 'boolean',
        'x-matches' => 'The value must be the same as the value of the test_confirmed property.',
    ]);

it('applies the x-matches annotation to an integer schema')
    ->expect(fn () => $this->class
        ->addIntegerProperty('test', [Confirmed::class])
        ->addIntegerProperty('test_confirmed')
    )
    ->toHaveSchema('test', [
        'type' => 'integer',
        'x-matches' => 'The value must be the same as the value of the test_confirmed property.',
    ]);

it('applies the x-matches annotation to a number schema')
    ->expect(fn () => $this->class
        ->addNumberProperty('test', [Confirmed::class])
        ->addNumberProperty('test_confirmed')
    )
    ->toHaveSchema('test', [
        'type' => 'number',
        'x-matches' => 'The value must be the same as the value of the test_confirmed property.',
    ]);

it('applies the x-matches annotation to a string schema')
    ->expect(fn () => $this->class
        ->addStringProperty('test', [Confirmed::class])
        ->addStringProperty('test_confirmed')
    )
    ->toHaveSchema('test', [
        'type' => 'string',
        'x-matches' => 'The value must be the same as the value of the test_confirmed property.',
    ]);
