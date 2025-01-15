<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\UrlRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Url;

covers(UrlRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the format keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Url::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'format' => 'uri',
    ]);
