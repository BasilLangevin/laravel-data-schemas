<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\BooleanTypeRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\BooleanType;

covers(BooleanTypeRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the enum keyword to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [BooleanType::class]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'enum' => [0, 1],
    ]);

it('applies the enum keyword to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [BooleanType::class]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'enum' => [0, 1],
    ]);

it('applies the enum keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [BooleanType::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'enum' => ['0', '1'],
    ]);
