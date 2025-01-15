<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\MacAddressRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\MacAddress;

covers(MacAddressRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the x-mac-address keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [MacAddress::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'x-mac-address' => 'The value must be a MAC address.',
    ]);
