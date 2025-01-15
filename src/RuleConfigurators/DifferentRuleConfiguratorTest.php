<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\DifferentRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Different;

covers(DifferentRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the x-different-than annotation to a boolean schema')
    ->expect(fn () => $this->class
        ->addBooleanProperty('test', [Different::class => 'other_field'])
        ->addBooleanProperty('other_field')
    )
    ->toHaveSchema('test', [
        'type' => 'boolean',
        'x-different-than' => 'The value must be different from the value of the other_field property.',
    ]);

it('applies the x-different-than annotation to an integer schema')
    ->expect(fn () => $this->class
        ->addIntegerProperty('test', [Different::class => 'other_field'])
        ->addIntegerProperty('other_field')
    )
    ->toHaveSchema('test', [
        'type' => 'integer',
        'x-different-than' => 'The value must be different from the value of the other_field property.',
    ]);

it('applies the x-different-than annotation to a number schema')
    ->expect(fn () => $this->class
        ->addNumberProperty('test', [Different::class => 'other_field'])
        ->addNumberProperty('other_field')
    )
    ->toHaveSchema('test', [
        'type' => 'number',
        'x-different-than' => 'The value must be different from the value of the other_field property.',
    ]);

it('applies the x-different-than annotation to a string schema')
    ->expect(fn () => $this->class
        ->addStringProperty('test', [Different::class => 'other_field'])
        ->addStringProperty('other_field')
    )
    ->toHaveSchema('test', [
        'type' => 'string',
        'x-different-than' => 'The value must be different from the value of the other_field property.',
    ]);
