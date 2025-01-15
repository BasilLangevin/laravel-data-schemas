<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\LessThanRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\LessThan;

covers(LessThanRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the maxItems keyword to an array schema')
    ->expect(fn () => $this->class->addArrayProperty('test', [LessThan::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'array',
        'maxItems' => 9,
    ]);

it('applies the x-less-than annotation to an array schema when comparing to another field')
    ->todo()
    ->expect(fn () => $this->class
        ->addArrayProperty('test', [LessThan::class => 'other'])
        ->addArrayProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'array',
        'x-less-than' => 'The value must have fewer items than the other property.',
    ]);

it('applies the exclusiveMaximum keyword to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [LessThan::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'exclusiveMaximum' => 10,
    ]);

it('applies the x-less-than annotation to an integer schema when comparing to another field')
    ->expect(fn () => $this->class
        ->addIntegerProperty('test', [LessThan::class => 'other'])
        ->addIntegerProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'integer',
        'x-less-than' => 'The value must be less than the value of other.',
    ]);

it('applies the exclusiveMaximum keyword to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [LessThan::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'exclusiveMaximum' => 10,
    ]);

it('applies the x-less-than annotation to a number schema when comparing to another field')
    ->expect(fn () => $this->class
        ->addNumberProperty('test', [LessThan::class => 'other'])
        ->addNumberProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'number',
        'x-less-than' => 'The value must be less than the value of other.',
    ]);

it('applies the maxProperties keyword to an object schema')
    ->expect(fn () => $this->class->addObjectProperty('test', [LessThan::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'object',
        'maxProperties' => 9,
    ]);

it('applies the x-less-than annotation to an object schema when comparing to another field')
    ->todo()
    ->expect(fn () => $this->class
        ->addObjectProperty('test', [LessThan::class => 'other'])
        ->addObjectProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'object',
        'x-less-than' => 'The value must have fewer properties than the other property.',
    ]);

it('applies the maxLength keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [LessThan::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'maxLength' => 9,
    ]);

it('applies the x-less-than annotation to a string schema when comparing to another field')
    ->expect(fn () => $this->class->addStringProperty('test', [LessThan::class => 'other']))
    ->toHaveSchema('test', [
        'type' => 'string',
        'x-less-than' => 'The value must have fewer characters than the value of other.',
    ]);
