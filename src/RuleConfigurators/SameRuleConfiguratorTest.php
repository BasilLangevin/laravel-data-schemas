<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\SameRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Same;

covers(SameRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the x-matches annotation to a boolean schema')
    ->expect(fn () => $this->class
        ->addBooleanProperty('test', [Same::class => 'other_field'])
        ->addBooleanProperty('other_field')
    )
    ->toHaveSchema('test', [
        'type' => 'boolean',
        'x-matches' => 'The value must be the same as the value of the other_field property.',
    ]);

it('applies the x-matches annotation to an integer schema')
    ->expect(fn () => $this->class
        ->addIntegerProperty('test', [Same::class => 'other_field'])
        ->addIntegerProperty('other_field')
    )
    ->toHaveSchema('test', [
        'type' => 'integer',
        'x-matches' => 'The value must be the same as the value of the other_field property.',
    ]);

it('applies the x-matches annotation to a number schema')
    ->expect(fn () => $this->class
        ->addNumberProperty('test', [Same::class => 'other_field'])
        ->addNumberProperty('other_field')
    )
    ->toHaveSchema('test', [
        'type' => 'number',
        'x-matches' => 'The value must be the same as the value of the other_field property.',
    ]);

it('applies the x-matches annotation to a string schema')
    ->expect(fn () => $this->class
        ->addStringProperty('test', [Same::class => 'other_field'])
        ->addStringProperty('other_field')
    )
    ->toHaveSchema('test', [
        'type' => 'string',
        'x-matches' => 'The value must be the same as the value of the other_field property.',
    ]);
