<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\General\TypeKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;

covers(Schema::class);

class SchemaTestSchema extends Schema
{
    public static DataType $type = DataType::Boolean;

    public static array $keywords = [
        TypeKeyword::class,
        DescriptionKeyword::class,
    ];
}

it('can be created with the make method')
    ->expect(SchemaTestSchema::make('test', 'test description'))
    ->toBeInstanceOf(SchemaTestSchema::class)
    ->getName()->toBe('test')
    ->getDescription()->toBe('test description');

it('has a name')
    ->expect(new SchemaTestSchema('test'))
    ->getName()->toBe('test');

it('can leave the name empty')
    ->expect(SchemaTestSchema::make())
    ->getName()->toBe('');

it('can set its name after construction')
    ->expect(SchemaTestSchema::make())
    ->name('test')
    ->getName()->toBe('test');

it('can set its description on construction')
    ->expect(fn () => SchemaTestSchema::make('test', 'test description'))
    ->getDescription()->toBe('test description');

it('can set its description after construction')
    ->expect(SchemaTestSchema::make('test'))
    ->description('test description')
    ->getDescription()->toBe('test description');

it('can convert to an array')
    ->expect(SchemaTestSchema::make('test'))
    ->description('test description')
    ->toArray()
    ->toBe([
        'description' => 'test description',
    ]);

it('can clone its base structure', function () {
    $schema = SchemaTestSchema::make('test');
    $schema->description('test description');

    $clone = $schema->cloneBaseStructure();

    expect($clone)->toBeInstanceOf(SchemaTestSchema::class);
    expect($clone->getName())->toBe('');
    expect(fn () => $clone->getDescription())->toThrow(Exception::class, 'The keyword "description" has not been set.');
});

it('can pipe itself to a callback')
    ->expect(SchemaTestSchema::make('test')->pipe(fn (Schema $schema) => $schema->description('test description')))
    ->getDescription()->toBe('test description');

test('the pipe method returns the schema', function () {
    $schema = SchemaTestSchema::make('test');
    $result = $schema->pipe(fn (Schema $schema) => $schema->description('test description'));

    expect($result)->toBe($schema);
});

it('has a when method that applies a callback when a condition is true')
    ->expect(SchemaTestSchema::make('test')->when(true, fn (Schema $schema) => $schema->description('test description')))
    ->getDescription()->toBe('test description')
    ->expect(SchemaTestSchema::make()->when(false, fn (Schema $schema) => $schema->name('test name')))
    ->getName()->toBe('');
