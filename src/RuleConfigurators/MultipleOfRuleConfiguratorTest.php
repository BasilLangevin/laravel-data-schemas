<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\MultipleOfRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\MultipleOf;

covers(MultipleOfRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the multipleOf keyword to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [MultipleOf::class => 3]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'multipleOf' => 3,
    ]);

it('applies the multipleOf keyword to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [MultipleOf::class => 2]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'multipleOf' => 2,
    ]);
