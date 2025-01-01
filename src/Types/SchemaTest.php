<?php

use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Types\Schema;

class SchemaTestSchema extends Schema
{
    public static array $keywords = [
        DescriptionKeyword::class,
    ];

    public function toArray(): array
    {
        return [];
    }
}

it('has a name', function () {
    $schema = new SchemaTestSchema('test');

    expect($schema->name())->toBe('test');
});

it('can set its name after construction', function () {
    $schema = new SchemaTestSchema;
    $schema->setName('test');

    expect($schema->name())->toBe('test');
});

it('can set its description on construction', function () {
    $schema = new SchemaTestSchema('test', 'test description');

    expect($schema->getDescription())->toBe('test description');
});

it('can set its description after construction', function () {
    $schema = new SchemaTestSchema('test');
    $schema->description('test description');

    expect($schema->getDescription())->toBe('test description');
});
