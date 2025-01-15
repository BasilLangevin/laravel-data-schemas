<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Concerns\ResolvesPropertyName;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Argument;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Different;

covers(ResolvesPropertyName::class);

uses(TestsSchemaTransformation::class);

it('parses the property value correctly', function (mixed $argument) {
    $this->class->addStringProperty('property1', [Different::class => $argument]);
    $this->class->addStringProperty('property2');

    $annotation = Arr::get($this->class->getSchema('property1'), 'x-different-than');

    expect($annotation)->toBe('The value must be different from the value of the property2 property.');
})->with([
    ['property2'],
    [new Argument("new Spatie\LaravelData\Support\Validation\References\FieldReference('property2')")],
]);
