<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\DistinctRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Distinct;

covers(DistinctRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the x-distinct annotation to a boolean schema')
    ->expect(fn () => $this->class->addBooleanProperty('test', [Distinct::class]))
    ->toHaveSchema('test', [
        'type' => 'boolean',
        'x-distinct' => 'The value of each test property must be unique.',
    ]);

it('applies the x-distinct annotation to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [Distinct::class]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'x-distinct' => 'The value of each test property must be unique.',
    ]);

it('applies the x-distinct annotation to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [Distinct::class]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'x-distinct' => 'The value of each test property must be unique.',
    ]);

it('applies the x-distinct annotation to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Distinct::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'x-distinct' => 'The value of each test property must be unique.',
    ]);
