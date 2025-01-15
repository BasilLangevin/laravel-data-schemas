<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Decoration\CustomAnnotationKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;

covers(CustomAnnotationKeyword::class);

$basicOutput = collect([
    'type' => DataType::String->value,
]);

it('can set a custom annotation')
    ->expect(StringSchema::make()->customAnnotation('test', 'value'))
    ->getCustomAnnotation()->toBe(['x-test' => 'value']);

it('can set a custom annotation with an array')
    ->expect(StringSchema::make()->customAnnotation(['test' => 'value']))
    ->getCustomAnnotation()->toBe(['x-test' => 'value']);

it('can set multiple custom annotations with an array')
    ->expect(StringSchema::make()->customAnnotation(['test' => 'value', 'test2' => 'value2']))
    ->getCustomAnnotation()->toBe(['x-test' => 'value', 'x-test2' => 'value2']);

it('can get its custom annotation')
    ->expect(StringSchema::make()->customAnnotation('test', 'value'))
    ->getCustomAnnotation()->toBe(['x-test' => 'value']);

it('can apply the custom annotation to a schema')
    ->expect(StringSchema::make()->customAnnotation('test', 'value'))
    ->applyKeyword(CustomAnnotationKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'x-test' => 'value',
    ]));

it('can apply multiple custom annotations to a schema')
    ->expect(StringSchema::make()->customAnnotation(['test' => 'value', 'test2' => 'value2']))
    ->applyKeyword(CustomAnnotationKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'x-test' => 'value',
        'x-test2' => 'value2',
    ]));

it('can apply multiple custom annotations to a schema with multiple instances')
    ->expect(StringSchema::make()->customAnnotation('test', 'value')->customAnnotation('test2', 'value2'))
    ->applyKeyword(CustomAnnotationKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'x-test' => 'value',
        'x-test2' => 'value2',
    ]));
