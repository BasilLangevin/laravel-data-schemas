<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\ArrayTypeRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\ArrayType;

covers(ArrayTypeRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the required keyword to an object schema')
    ->expect(fn () => $this->class->addObjectProperty('test', [ArrayType::class => [['first', 'second']]]))
    ->toHaveSchema('test', [
        'type' => 'object',
        'required' => ['first', 'second'],
    ]);
