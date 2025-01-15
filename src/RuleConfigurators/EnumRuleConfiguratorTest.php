<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\EnumRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Enums\TestIntegerEnum;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Enums\TestStringEnum;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Enum;

covers(EnumRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the enum and x-enum-values keywords to an integer schema')
    ->expect(fn () => $this->class->addIntegerProperty('test', [Enum::class => TestIntegerEnum::class]))
    ->toHaveSchema('test', [
        'type' => 'integer',
        'enum' => [1, 2],
        'x-enum-values' => [
            'One' => 1,
            'Two' => 2,
        ],
    ]);

it('applies the enum and x-enum-values keywords to a number schema')
    ->expect(fn () => $this->class->addNumberProperty('test', [Enum::class => TestIntegerEnum::class]))
    ->toHaveSchema('test', [
        'type' => 'number',
        'enum' => [1, 2],
        'x-enum-values' => [
            'One' => 1,
            'Two' => 2,
        ],
    ]);

it('applies the enum keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Enum::class => TestStringEnum::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'enum' => ['first', 'second'],
    ]);
