<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\JsonRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Json;

covers(JsonRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the x-json keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Json::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'x-json' => 'The value must be a valid JSON string.',
    ]);
