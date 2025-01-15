<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\AfterRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\After;

covers(AfterRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies format and x-date-after keywords to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [After::class => '2025-01-15']))
    ->toHaveSchema('test', [
        'type' => 'string',
        'format' => 'date-time',
        'x-date-after' => 'The value must be after 2025-01-15T00:00:00Z.',
    ]);
