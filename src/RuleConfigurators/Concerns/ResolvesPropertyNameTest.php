<?php

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Concerns\ResolvesPropertyName;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\LaravelData\Attributes\Validation\Different;
use Spatie\LaravelData\Attributes\Validation\GreaterThan;
use Spatie\LaravelData\Attributes\Validation\GreaterThanOrEqualTo;
use Spatie\LaravelData\Attributes\Validation\LessThan;
use Spatie\LaravelData\Attributes\Validation\LessThanOrEqualTo;
use Spatie\LaravelData\Attributes\Validation\Same;

covers(ResolvesPropertyName::class);

uses(TestsSchemaTransformation::class);

it('parses the property value correctly', function (string $attribute, string $annotation) {
    $this->class->addStringProperty('property1', [$attribute => 'property2']);
    $this->class->addStringProperty('property2');

    $annotation = Arr::get($this->class->getSchema('property1'), $annotation);

    expect(Str::contains($annotation, 'property2'))->toBeTrue();
})->with([
    [Different::class, 'x-different-than'],
    [GreaterThanOrEqualTo::class, 'x-greater-than-or-equal-to'],
    [GreaterThan::class, 'x-greater-than'],
    [LessThanOrEqualTo::class, 'x-less-than-or-equal-to'],
    [LessThan::class, 'x-less-than'],
    [Same::class, 'x-matches'],
]);
