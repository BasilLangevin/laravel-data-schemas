<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\IPv4RuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\IPv4;

covers(IPv4RuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the format keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [IPv4::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'format' => 'ipv4',
    ]);
