<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\InRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\In;

covers(InRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the enum keyword to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [In::class => [1, 2, 3]]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'enum' => [1, 2, 3],
    ]);

it('applies the enum keyword to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [In::class => [1.5, 2.5, 3.5]]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'enum' => [1.5, 2.5, 3.5],
    ]);

it('applies the enum keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [In::class => ['foo', 'bar', 'baz']]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'enum' => ['foo', 'bar', 'baz'],
    ]);
