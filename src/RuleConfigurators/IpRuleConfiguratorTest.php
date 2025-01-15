<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\IPRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\IP;

covers(IPRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the x-ip-address keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [IP::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'x-ip-address' => 'The value must be an IP address.',
    ]);
