<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\SizeRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Size;

covers(SizeRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the minItems and maxItems keywords to an array schema')
    ->todo()
    ->expect(fn () => $this->class->addArrayProperty('test', [Size::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'array',
        'minItems' => 10,
        'maxItems' => 10,
    ]);

it('applies the minimum and maximum keywords to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [Size::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'minimum' => 10,
        'maximum' => 10,
    ]);

it('applies the minimum and maximum keywords to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [Size::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'minimum' => 10,
        'maximum' => 10,
    ]);

it('applies the minProperties and maxProperties keywords to an object schema')
    ->todo()
    ->expect(fn () => $this->class->addObjectProperty('test', [Size::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'object',
        'minProperties' => 10,
        'maxProperties' => 10,
    ]);

it('applies the minLength and maxLength keywords to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Size::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'minLength' => 10,
        'maxLength' => 10,
    ]);
