<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\GreaterThanRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\GreaterThan;

covers(GreaterThanRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the minItems keyword to an array schema')
    ->expect(fn () => $this->class->addArrayProperty('test', [GreaterThan::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'array',
        'minItems' => 11,
    ]);

it('applies the x-greater-than annotation to an array schema when comparing to another field')
    ->todo()
    ->expect(fn () => $this->class
        ->addArrayProperty('test', [GreaterThan::class => 'other'])
        ->addArrayProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'array',
        'x-greater-than' => 'The value must have more items than the other property.',
    ]);

it('applies the exclusiveMinimum keyword to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [GreaterThan::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'exclusiveMinimum' => 10,
    ]);

it('applies the x-greater-than annotation to an integer schema when comparing to another field')
    ->expect(fn () => $this->class
        ->addIntegerProperty('test', [GreaterThan::class => 'other'])
        ->addIntegerProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'integer',
        'x-greater-than' => 'The value must be greater than the value of other.',
    ]);

it('applies the exclusiveMinimum keyword to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [GreaterThan::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'exclusiveMinimum' => 10,
    ]);

it('applies the x-greater-than annotation to a number schema when comparing to another field')
    ->expect(fn () => $this->class
        ->addNumberProperty('test', [GreaterThan::class => 'other'])
        ->addNumberProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'number',
        'x-greater-than' => 'The value must be greater than the value of other.',
    ]);

it('applies the minProperties keyword to an object schema')
    ->todo()
    ->expect(fn () => $this->class->addObjectProperty('test', [GreaterThan::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'object',
        'minProperties' => 11,
    ]);

it('applies the x-greater-than annotation to an object schema when comparing to another field')
    ->todo()
    ->expect(fn () => $this->class
        ->addObjectProperty('test', [GreaterThan::class => 'other'])
        ->addObjectProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'object',
        'x-greater-than' => 'The value must have more properties than the other property.',
    ]);

it('applies the minLength keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [GreaterThan::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'minLength' => 11,
    ]);

it('applies the x-greater-than annotation to a string schema when comparing to another field')
    ->expect(fn () => $this->class->addStringProperty('test', [GreaterThan::class => 'other']))
    ->toHaveSchema('test', [
        'type' => 'string',
        'x-greater-than' => 'The value must have more characters than the value of other.',
    ]);
