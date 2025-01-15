<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\AlphaRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Alpha;

covers(AlphaRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the pattern keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Alpha::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'pattern' => '/^[a-zA-Z]+$/',
    ]);
