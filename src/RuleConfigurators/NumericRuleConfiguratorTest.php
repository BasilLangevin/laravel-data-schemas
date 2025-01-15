<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\NumericRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Numeric;

covers(NumericRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the pattern keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Numeric::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'pattern' => '/^-?(\d+|\d*\.\d+)([eE][+-]?\d+)?$/',
        'x-numeric' => 'The value must be numeric.',
    ]);
