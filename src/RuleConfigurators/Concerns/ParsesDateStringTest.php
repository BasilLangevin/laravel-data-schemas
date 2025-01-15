<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Concerns\ParsesDateString;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Argument;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\After;

covers(ParsesDateString::class);

uses(TestsSchemaTransformation::class);

it('parses the date value correctly', function (mixed $argument, string $expected) {
    $this->class->addStringProperty('test', [After::class => $argument]);

    $annotation = Arr::get($this->class->getSchema('test'), 'x-date-after');

    $dateString = str($annotation)
        ->after('The value must be after ')
        ->before('.')
        ->value();

    expect($dateString)->toBe($expected);
})->with([
    ['today', Carbon::parse('today')->toIso8601ZuluString()],
    [new Argument("new Carbon\Carbon('2025-01-15')"), '2025-01-15T00:00:00Z'],
    [new Argument("new Carbon\Carbon('2025-01-15 12:00:00')"), '2025-01-15T12:00:00Z'],
    [new Argument("new Carbon\Carbon('2025-01-15 12:00:00', 'America/New_York')"), '2025-01-15T12:00:00-05:00'],
]);

it('parses the property value correctly', function () {
    $this->class->addStringProperty('property1', [After::class => 'property2']);
    $this->class->addStringProperty('property2');

    $annotation = Arr::get($this->class->getSchema('property1'), 'x-date-after');

    expect($annotation)->toBe('The value must be after the value of property2.');
});
