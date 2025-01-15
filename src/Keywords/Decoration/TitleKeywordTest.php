<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Decoration\TitleKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;

covers(TitleKeyword::class);

class TitleKeywordTestSchema extends Schema
{
    public static array $keywords = [
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
