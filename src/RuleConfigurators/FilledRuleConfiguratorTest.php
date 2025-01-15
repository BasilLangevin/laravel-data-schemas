<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\FilledRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Filled;

covers(FilledRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the minItems keyword to an array schema')
    ->expect(fn () => $this->class->addArrayProperty('test', [Filled::class]))
    ->toHaveSchema('test', [
        'type' => 'array',
        'minItems' => 1,
    ]);

it('applies the not keyword to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [Filled::class]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'not' => [
            'const' => 0,
        ],
    ]);

it('applies the not keyword to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [Filled::class]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'not' => [
            'const' => 0,
        ],
    ]);

it('applies the minProperties keyword to an object schema')
    ->expect(fn () => $this->class->addObjectProperty('test', [Filled::class]))
    ->toHaveSchema('test', [
        'type' => 'object',
        'minProperties' => 1,
    ]);

it('applies the minLength keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Filled::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'minLength' => 1,
    ]);
