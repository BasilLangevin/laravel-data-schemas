<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\MaxRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Max;

covers(MaxRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the maxLength keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Max::class => '10']))
    ->toHaveSchema('test', [
        'type' => 'string',
        'maxLength' => 10,
    ]);

it('applies the maximum keyword to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [Max::class => '10']))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'maximum' => 10,
    ]);
