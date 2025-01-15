<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\TitleKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\General\TypeKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;

covers(TitleKeyword::class);

class TitleKeywordTestSchema extends StringSchema
{
    public static array $keywords = [
        TypeKeyword::class,
        TitleKeyword::class,
    ];
}

$basicOutput = collect([
    'type' => DataType::String->value,
]);

it('can set its title')
    ->expect(TitleKeywordTestSchema::make()->title('test title'))
    ->getTitle()->toBe('test title');

it('can apply the title to a schema')
    ->expect(TitleKeywordTestSchema::make())
    ->title('test title')
    ->applyKeyword(TitleKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'title' => 'test title',
    ]));
