<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\BeforeRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Before;

covers(BeforeRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies format and x-date-before keywords to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Before::class => '2025-01-15']))
    ->toHaveSchema('test', [
        'type' => 'string',
        'format' => 'date-time',
        'x-date-before' => 'The value must be before 2025-01-15T00:00:00Z.',
    ]);
