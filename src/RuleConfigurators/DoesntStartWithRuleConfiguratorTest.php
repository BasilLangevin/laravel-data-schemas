<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\DoesntStartWithRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\DoesntStartWith;

covers(DoesntStartWithRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the pattern and custom annotation keywords to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [DoesntStartWith::class => ['foo', 'bar']]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'pattern' => '/^(?!foo|bar).*$/',
        'x-doesnt-start-with' => 'The value must not start with "foo" or "bar".',
    ]);

it('applies the pattern and custom annotation keywords to a string schema with a single value')
    ->expect(fn () => $this->class->addStringProperty('test', [DoesntStartWith::class => ['foo']]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'pattern' => '/^(?!foo).*$/',
        'x-doesnt-start-with' => 'The value must not start with "foo".',
    ]);
