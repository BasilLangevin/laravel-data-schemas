<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;

covers(Schema::class);

class SchemaTestSchema extends Schema
{
    public static DataType $type = DataType::Boolean;

    public static array $keywords = [
        DescriptionKeyword::class,
    ];
}

it('can be created with the make method')
    ->expect(SchemaTestSchema::make('test', 'test description'))
    ->toBeInstanceOf(SchemaTestSchema::class)
    ->name()->toBe('test')
    ->getDescription()->toBe('test description');

it('has a name')
    ->expect(new SchemaTestSchema('test'))
    ->name()->toBe('test');

it('can leave the name empty')
    ->expect(SchemaTestSchema::make())
    ->name()->toBe('');

it('can set its name after construction')
    ->expect(SchemaTestSchema::make())
    ->setName('test')
    ->name()->toBe('test');

/**
 * For some reason, test coverage doesn't detect the description
 * being set on construction unless it's in long form.
 */
it('can set its description on construction', function () {
    $schema = SchemaTestSchema::make('test', 'test description');

    expect($schema->getDescription())->toBe('test description');
});

it('can set its description after construction')
    ->expect(SchemaTestSchema::make('test'))
    ->description('test description')
    ->getDescription()->toBe('test description');

it('can convert to an array')
    ->expect(SchemaTestSchema::make('test'))
    ->description('test description')
    ->toArray()
    ->toBe([
        'type' => DataType::Boolean->value,
        'description' => 'test description',
    ]);
