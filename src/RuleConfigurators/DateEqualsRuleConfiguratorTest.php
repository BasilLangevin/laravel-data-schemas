<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\DateEqualsRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\DateEquals;

covers(DateEqualsRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the format and const keywords to a string schema')
    ->todo()
    ->expect(fn () => $this->class->addStringProperty('test', [DateEquals::class => Carbon::parse('2025-01-01')]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'format' => 'date-time',
        'const' => '2023-10-01T00:00:00Z',
    ]);
