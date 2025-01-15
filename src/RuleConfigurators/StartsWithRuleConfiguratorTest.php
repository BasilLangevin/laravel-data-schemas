<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\StartsWithRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\StartsWith;

covers(StartsWithRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the pattern and custom annotation keywords to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [StartsWith::class => ['foo', 'bar']]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'pattern' => '/^(foo|bar)/',
        'x-starts-with' => 'The value must start with "foo" or "bar".',
    ]);

it('applies the pattern and custom annotation keywords to a string schema with a single value')
    ->expect(fn () => $this->class->addStringProperty('test', [StartsWith::class => ['foo']]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'pattern' => '/^(foo)/',
        'x-starts-with' => 'The value must start with "foo".',
    ]);
