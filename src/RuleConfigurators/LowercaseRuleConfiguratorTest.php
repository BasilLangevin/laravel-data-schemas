<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\LowercaseRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Lowercase;

covers(LowercaseRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the pattern and custom annotation keywords to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Lowercase::class => []]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'pattern' => '/^[^A-Z]+$/',
        'x-lowercase' => 'The value must be lowercase.',
    ]);
