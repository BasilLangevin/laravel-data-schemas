<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\MinDigitsRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\MinDigits;

covers(MinDigitsRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the minimum and x-min-digits keywords to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [MinDigits::class => 3]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'minimum' => 100,
        'x-min-digits' => 'The value must have at least 3 digits.',
    ]);

it('applies the minimum and x-min-digits keywords to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [MinDigits::class => 2]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'minimum' => 10,
        'x-min-digits' => 'The value must have at least 2 digits.',
    ]);
