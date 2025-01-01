<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Types\Schema;

covers(DescriptionKeyword::class);

class DescriptionKeywordTestSchema extends Schema
{
    public static array $keywords = [
        DescriptionKeyword::class,
    ];
}

it('can set its description on construction', function () {
    $schema = new DescriptionKeywordTestSchema('test', 'test description');

    expect($schema->getDescription())->toBe('test description');
});

it('can set its description after construction', function () {
    $schema = new DescriptionKeywordTestSchema('test');
    $schema->description('test description');

    expect($schema->getDescription())->toBe('test description');
});

it('can apply the description to a schema', function () {
    $schema = new DescriptionKeywordTestSchema('test');
    $schema->description('test description');

    $data = collect([
        'type' => DataType::String->value,
    ]);

    $result = $schema->applyKeyword(DescriptionKeyword::class, $data);

    expect($result)->toEqual(collect([
        'type' => DataType::String->value,
        'description' => 'test description',
    ]));
});
