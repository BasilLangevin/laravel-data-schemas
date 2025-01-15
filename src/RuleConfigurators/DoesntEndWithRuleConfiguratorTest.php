<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\DoesntEndWithRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\DoesntEndWith;

covers(DoesntEndWithRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the pattern and custom annotation keywords to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [DoesntEndWith::class => ['foo', 'bar']]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'pattern' => '/^(?!.*(foo|bar)$).*$/',
        'x-doesnt-end-with' => 'The value must not end with "foo" or "bar".',
    ]);

it('applies the pattern and custom annotation keywords to a string schema with a single value')
    ->expect(fn () => $this->class->addStringProperty('test', [DoesntEndWith::class => ['foo']]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'pattern' => '/^(?!.*(foo)$).*$/',
        'x-doesnt-end-with' => 'The value must not end with "foo".',
    ]);
