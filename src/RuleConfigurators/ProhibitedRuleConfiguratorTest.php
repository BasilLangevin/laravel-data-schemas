<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\ProhibitedRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Prohibited;

covers(ProhibitedRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the maxItems keyword to an array schema')
    ->expect(fn () => $this->class->addArrayProperty('test', [Prohibited::class]))
    ->toHaveSchema('test', [
        'type' => 'array',
        'maxItems' => 0,
    ]);

it('applies the const keyword to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [Prohibited::class]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'const' => 0,
    ]);

it('applies the const keyword to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [Prohibited::class]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'const' => 0,
    ]);

it('applies the maxProperties keyword to an object schema')
    ->expect(fn () => $this->class->addObjectProperty('test', [Prohibited::class]))
    ->toHaveSchema('test', [
        'type' => 'object',
        'maxProperties' => 0,
    ]);

it('applies the maxLength keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Prohibited::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'maxLength' => 0,
    ]);
