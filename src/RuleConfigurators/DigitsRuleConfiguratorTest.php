<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\DigitsRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Digits;

covers(DigitsRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the minimum, maximum and x-digits keywords to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [Digits::class => 3]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'minimum' => 100,
        'maximum' => 999,
        'x-digits' => 'The value must have 3 digits.',
    ]);

it('applies the minimum, maximum and x-digits keywords to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [Digits::class => 2]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'minimum' => 10,
        'maximum' => 99,
        'x-digits' => 'The value must have 2 digits.',
    ]);
