<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\ActiveUrlRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\ActiveUrl;

covers(ActiveUrlRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the format keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [ActiveUrl::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'format' => 'uri',
    ]);
