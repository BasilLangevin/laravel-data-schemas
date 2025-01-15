<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\GreaterThanOrEqualToRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\GreaterThanOrEqualTo;

covers(GreaterThanOrEqualToRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the minItems keyword to an array schema')
    ->expect(fn () => $this->class->addArrayProperty('test', [GreaterThanOrEqualTo::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'array',
        'minItems' => 10,
    ]);

it('applies the x-greater-than-or-equal-to annotation to an array schema when comparing to another field')
    ->expect(fn () => $this->class
        ->addArrayProperty('test', [GreaterThanOrEqualTo::class => 'other'])
        ->addArrayProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'array',
        'x-greater-than-or-equal-to' => 'The value must have at least as many items as the other property.',
    ]);

it('applies the minimum keyword to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [GreaterThanOrEqualTo::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'minimum' => 10,
    ]);

it('applies the x-greater-than-or-equal-to annotation to an integer schema when comparing to another field')
    ->expect(fn () => $this->class
        ->addIntegerProperty('test', [GreaterThanOrEqualTo::class => 'other'])
        ->addIntegerProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'integer',
        'x-greater-than-or-equal-to' => 'The value must be greater than or equal to the value of other.',
    ]);

it('applies the minimum keyword to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [GreaterThanOrEqualTo::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'minimum' => 10,
    ]);

it('applies the x-greater-than-or-equal-to annotation to a number schema when comparing to another field')
    ->expect(fn () => $this->class
        ->addNumberProperty('test', [GreaterThanOrEqualTo::class => 'other'])
        ->addNumberProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'number',
        'x-greater-than-or-equal-to' => 'The value must be greater than or equal to the value of other.',
    ]);

it('applies the minProperties keyword to an object schema')
    ->expect(fn () => $this->class->addObjectProperty('test', [GreaterThanOrEqualTo::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'object',
        'minProperties' => 10,
    ]);

it('applies the x-greater-than-or-equal-to annotation to an object schema when comparing to another field')
    ->expect(fn () => $this->class
        ->addObjectProperty('test', [GreaterThanOrEqualTo::class => 'other'])
        ->addObjectProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'object',
        'x-greater-than-or-equal-to' => 'The value must have at least as many properties as the other property.',
    ]);

it('applies the minLength keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [GreaterThanOrEqualTo::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'minLength' => 10,
    ]);

it('applies the x-greater-than-or-equal-to annotation to a string schema when comparing to another field')
    ->expect(fn () => $this->class->addStringProperty('test', [GreaterThanOrEqualTo::class => 'other']))
    ->toHaveSchema('test', [
        'type' => 'string',
        'x-greater-than-or-equal-to' => 'The value must have at least as many characters as the value of other.',
    ]);
