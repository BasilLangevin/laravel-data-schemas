<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\MaxDigitsRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\MaxDigits;

covers(MaxDigitsRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the maximum and x-max-digits keywords to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [MaxDigits::class => 3]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'maximum' => 999,
        'x-max-digits' => 'The value must not have more than 3 digits.',
    ]);

it('applies the maximum and x-max-digits keywords to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [MaxDigits::class => 2]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'maximum' => 99,
        'x-max-digits' => 'The value must not have more than 2 digits.',
    ]);
