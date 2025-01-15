<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\LessThanOrEqualToRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\LessThanOrEqualTo;

covers(LessThanOrEqualToRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the maxItems keyword to an array schema')
    ->expect(fn () => $this->class->addArrayProperty('test', [LessThanOrEqualTo::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'array',
        'maxItems' => 10,
    ]);

it('applies the x-less-than-or-equal-to annotation to an array schema when comparing to another field')
    ->expect(fn () => $this->class
        ->addArrayProperty('test', [LessThanOrEqualTo::class => 'other'])
        ->addArrayProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'array',
        'x-less-than-or-equal-to' => 'The value must have at most as many items as the other property.',
    ]);

it('applies the maximum keyword to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [LessThanOrEqualTo::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'maximum' => 10,
    ]);

it('applies the x-less-than-or-equal-to annotation to an integer schema when comparing to another field')
    ->expect(fn () => $this->class
        ->addIntegerProperty('test', [LessThanOrEqualTo::class => 'other'])
        ->addIntegerProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'integer',
        'x-less-than-or-equal-to' => 'The value must be less than or equal to the value of other.',
    ]);

it('applies the maximum keyword to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [LessThanOrEqualTo::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'maximum' => 10,
    ]);

it('applies the x-less-than-or-equal-to annotation to a number schema when comparing to another field')
    ->expect(fn () => $this->class
        ->addNumberProperty('test', [LessThanOrEqualTo::class => 'other'])
        ->addNumberProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'number',
        'x-less-than-or-equal-to' => 'The value must be less than or equal to the value of other.',
    ]);

it('applies the maxProperties keyword to an object schema')
    ->expect(fn () => $this->class->addObjectProperty('test', [LessThanOrEqualTo::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'object',
        'maxProperties' => 10,
    ]);

it('applies the x-less-than-or-equal-to annotation to an object schema when comparing to another field')
    ->expect(fn () => $this->class
        ->addObjectProperty('test', [LessThanOrEqualTo::class => 'other'])
        ->addObjectProperty('other')
    )
    ->toHaveSchema('test', [
        'type' => 'object',
        'x-less-than-or-equal-to' => 'The value must have at most as many properties as the other property.',
    ]);

it('applies the maxLength keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [LessThanOrEqualTo::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'maxLength' => 10,
    ]);

it('applies the x-less-than-or-equal-to annotation to a string schema when comparing to another field')
    ->expect(fn () => $this->class->addStringProperty('test', [LessThanOrEqualTo::class => 'other']))
    ->toHaveSchema('test', [
        'type' => 'string',
        'x-less-than-or-equal-to' => 'The value must have at most as many characters as the value of other.',
    ]);
