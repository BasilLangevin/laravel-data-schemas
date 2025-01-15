<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\NotInRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\NotIn;

covers(NotInRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the not enum keyword to an integer schema')
    ->todo()
    ->expect(fn () => $this->class->addIntegerProperty('test', [NotIn::class => [1, 2, 3]]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'not' => [
            'enum' => [1, 2, 3],
        ],
    ]);

it('applies the not enum keyword to a number schema')
    ->todo()
    ->expect(fn () => $this->class->addNumberProperty('test', [NotIn::class => [1.5, 2.5, 3.5]]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'not' => [
            'enum' => [1.5, 2.5, 3.5],
        ],
    ]);

it('applies the not enum keyword to a string schema')
    ->todo()
    ->expect(fn () => $this->class->addStringProperty('test', [NotIn::class => ['foo', 'bar', 'baz']]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'not' => [
            'enum' => ['foo', 'bar', 'baz'],
        ],
    ]);
