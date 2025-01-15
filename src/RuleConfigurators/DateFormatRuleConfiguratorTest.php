<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\DateFormatRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\DateFormat;

covers(DateFormatRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies format and x-date-format keywords to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [DateFormat::class => ['Y-m-d', 'Y-m-d H:i:s']]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'x-date-format' => 'The value must match the format "Y-m-d" or "Y-m-d H:i:s".',
    ]);
