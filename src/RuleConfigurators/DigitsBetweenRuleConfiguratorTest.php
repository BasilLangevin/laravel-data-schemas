<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\DigitsBetweenRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\DigitsBetween;

covers(DigitsBetweenRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the minimum, maximum and x-digits-between keywords to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [DigitsBetween::class => [3, 5]]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'minimum' => 100,
        'maximum' => 99999,
        'x-digits-between' => 'The value must have between 3 and 5 digits.',
    ]);

it('applies the minimum, maximum and x-digits-between keywords to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [DigitsBetween::class => [2, 4]]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'minimum' => 10,
        'maximum' => 9999,
        'x-digits-between' => 'The value must have between 2 and 4 digits.',
    ]);
