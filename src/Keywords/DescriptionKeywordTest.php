<?php

use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Types\Schema;

class DescriptionKeywordTestSchema extends Schema
{
    public static array $keywords = [
        DescriptionKeyword::class,
    ];

    public function toArray(): array
    {
        return [
            'description' => $this->getDescription(),
        ];
    }
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
