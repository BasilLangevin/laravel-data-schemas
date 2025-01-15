<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\UppercaseRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Uppercase;

covers(UppercaseRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the pattern and custom annotation keywords to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Uppercase::class => []]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'pattern' => '/^[^a-z]+$/',
        'x-uppercase' => 'The value must be uppercase.',
    ]);
