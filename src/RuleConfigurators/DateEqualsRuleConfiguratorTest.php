<?php

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\DateEquals;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Argument;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\DateEqualsRuleConfigurator;

covers(DateEqualsRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the format and const keywords to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [DateEquals::class => new Argument("new Carbon\Carbon('2025-01-01')")]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'format' => 'date-time',
        'const' => '2025-01-01T00:00:00Z',
    ]);
