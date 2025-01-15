<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\AcceptedRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Accepted;

covers(AcceptedRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the const keyword to a boolean schema')
    ->expect(fn () => $this->class->addBooleanProperty('test', [Accepted::class]))
    ->toHaveSchema('test', [
        'type' => 'boolean',
        'const' => true,
    ]);

it('applies the const keyword to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [Accepted::class]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'const' => 1,
    ]);

it('applies the const keyword to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [Accepted::class]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'const' => 1,
    ]);

it('applies the enum keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Accepted::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'enum' => ['yes', 'on', '1', 'true'],
    ]);
