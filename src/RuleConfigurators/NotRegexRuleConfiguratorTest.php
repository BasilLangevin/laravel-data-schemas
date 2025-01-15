<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\NotRegexRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\NotRegex;

covers(NotRegexRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the not pattern keyword to a string schema')
    ->todo()
    ->expect(fn () => $this->class->addStringProperty('test', [NotRegex::class => '/^[0-9]+$/']))
    ->toHaveSchema('test', [
        'type' => 'string',
        'not' => [
            'pattern' => '/^[0-9]+$/',
        ],
    ]);
