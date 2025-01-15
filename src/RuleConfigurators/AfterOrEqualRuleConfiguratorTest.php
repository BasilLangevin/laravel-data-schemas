<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\AfterOrEqualRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;

covers(AfterOrEqualRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies format and x-date-after-or-equal keywords to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [AfterOrEqual::class => '2025-01-15']))
    ->toHaveSchema('test', [
        'type' => 'string',
        'format' => 'date-time',
        'x-date-after-or-equal' => 'The value must be after or equal to 2025-01-15T00:00:00Z.',
    ]);
