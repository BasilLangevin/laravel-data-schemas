<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\EmailRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Email;

covers(EmailRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the format keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Email::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'format' => 'email',
    ]);
