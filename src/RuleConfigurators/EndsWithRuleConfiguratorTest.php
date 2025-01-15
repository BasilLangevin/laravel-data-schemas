<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\EndsWithRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\EndsWith;

covers(EndsWithRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the pattern and custom annotation keywords to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [EndsWith::class => ['foo', 'bar']]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'pattern' => '/(foo|bar)$/',
        'x-ends-with' => 'The value must end with "foo" or "bar".',
    ]);

it('applies the pattern and custom annotation keywords to a string schema with a single value')
    ->expect(fn () => $this->class->addStringProperty('test', [EndsWith::class => ['foo']]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'pattern' => '/(foo)$/',
        'x-ends-with' => 'The value must end with "foo".',
    ]);
