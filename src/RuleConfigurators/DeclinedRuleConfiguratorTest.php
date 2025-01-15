<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\DeclinedRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Declined;

covers(DeclinedRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the const keyword to a boolean schema')
    ->expect(fn () => $this->class->addBooleanProperty('test', [Declined::class]))
    ->toHaveSchema('test', [
        'type' => 'boolean',
        'const' => false,
    ]);

it('applies the const keyword to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [Declined::class]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'const' => 0,
    ]);

it('applies the const keyword to a number schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [Declined::class]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'const' => 0,
    ]);

it('applies the enum keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Declined::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'enum' => ['no', 'off', '0', 'false'],
    ]);
