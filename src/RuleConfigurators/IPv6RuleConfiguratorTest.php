<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\IPv6RuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\IPv6;

covers(IPv6RuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the format keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [IPv6::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'format' => 'ipv6',
    ]);
