<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Types\Schema;

covers(Schema::class);

class SchemaTestSchema extends Schema
{
    public static DataType $type = DataType::Boolean;

    public static array $keywords = [
        DescriptionKeyword::class,
    ];
}

it('can be created with the make method', function () {
    $schema = SchemaTestSchema::make('test', 'test description');

    expect($schema)->toBeInstanceOf(SchemaTestSchema::class);
    expect($schema->name())->toBe('test');
    expect($schema->getDescription())->toBe('test description');
});

it('has a name', function () {
    $schema = new SchemaTestSchema('test');

    expect($schema->name())->toBe('test');
});

it('can leave the name empty', function () {
    $schema = new SchemaTestSchema;

    expect($schema->name())->toBe('');
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

it('can convert to an array', function () {
    $schema = new SchemaTestSchema('test');
    $schema->description('test description');

    expect($schema->toArray())->toBe([
        'type' => DataType::Boolean->value,
        'description' => 'test description',
    ]);
});
