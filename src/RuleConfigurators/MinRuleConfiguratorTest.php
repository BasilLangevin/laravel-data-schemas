<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\MinRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Min;

covers(MinRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the minItems keyword to an array schema')
    ->todo()
    ->expect(fn () => $this->class->addArrayProperty('test', [Min::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'array',
        'minItems' => 10,
    ]);

it('applies the minimum keyword to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [Min::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'minimum' => 10,
    ]);

it('applies the minProperties keyword to an object schema')
    ->todo()
    ->expect(fn () => $this->class->addObjectProperty('test', [Min::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'object',
        'minProperties' => 10,
    ]);

it('applies the minLength keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Min::class => 10]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'minLength' => 10,
    ]);
