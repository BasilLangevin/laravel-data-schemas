<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\BeforeOrEqualRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\BeforeOrEqual;

covers(BeforeOrEqualRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies format and x-date-before-or-equal keywords to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [BeforeOrEqual::class => '2025-01-15']))
    ->toHaveSchema('test', [
        'type' => 'string',
        'format' => 'date-time',
        'x-date-before-or-equal' => 'The value must be before or equal to 2025-01-15T00:00:00Z.',
    ]);
