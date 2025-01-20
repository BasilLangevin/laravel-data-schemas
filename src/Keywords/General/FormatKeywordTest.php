<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Enums\Format;
use BasilLangevin\LaravelDataSchemas\Exceptions\SchemaConfigurationException;
use BasilLangevin\LaravelDataSchemas\Keywords\General\FormatKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;

covers(FormatKeyword::class);

$basicOutput = collect([
    'type' => DataType::String->value,
]);

it('can set its format')
    ->expect(StringSchema::make()->format(Format::DateTime))
    ->getFormat()->toBe(Format::DateTime);

it('can get its format')
    ->expect(StringSchema::make()->format(Format::DateTime))
    ->getFormat()->toBe(Format::DateTime);

it('can apply the format to a schema')
    ->expect(StringSchema::make()->format(Format::DateTime))
    ->applyKeyword(FormatKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'format' => Format::DateTime->value,
    ]));

it('can apply a Format enum value to a schema')
    ->expect(StringSchema::make()->format(Format::DateTime->value))
    ->applyKeyword(FormatKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'format' => Format::DateTime->value,
    ]));

it('applies the format when multiple instances with the same format are applied')
    ->expect(StringSchema::make()->format(Format::DateTime)->format(Format::DateTime))
    ->applyKeyword(FormatKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'format' => Format::DateTime->value,
    ]));

it('throws an exception if multiple instances with different formats are applied', function () {
    StringSchema::make()->format(Format::DateTime)->format(Format::Email)->toArray();
})->throws(SchemaConfigurationException::class, 'A schema cannot have more than one format.');
