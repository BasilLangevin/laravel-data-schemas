<?php

use BadMethodCallException;
use BasilLangevin\LaravelDataSchemas\Concerns\HasKeywords;
use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Exceptions\KeywordNotSetException;
use BasilLangevin\LaravelDataSchemas\Keywords\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use Illuminate\Support\Collection;

covers(HasKeywords::class);

/**
 * Used to test that getter methods must start with get.
 *
 * Since the keywordGetterExists method checks that the fourth character is uppercase,
 * we need to use a keyword that starts has an uppercase fourth character on its method name.
 */
class TheTestKeyword extends Keyword
{
    public function get(): mixed
    {
        return 'test';
    }

    public function apply(Collection $schema): Collection
    {
        return $schema->merge([
            'result' => 'I was successfully applied',
        ]);
    }

    public static function parse(ReflectionHelper $reflector): ?string
    {
        return 'test';
    }
}

class HasKeywordsTestSchema extends Schema
{
    public static array $keywords = [
        DescriptionKeyword::class,
        TheTestKeyword::class,
    ];
}

it('can call a keyword method')
    ->expect(HasKeywordsTestSchema::make())
    ->description('This is a description')
    ->getDescription()
    ->toBe('This is a description');

it('can call a keyword method multiple times to replace the existing value')
    ->expect(HasKeywordsTestSchema::make())
    ->description('This is a description')
    ->description('This is a new description')
    ->getDescription()
    ->toBe('This is a new description');

it('can call a keyword getter method')
    ->expect(HasKeywordsTestSchema::make())
    ->description('This is a description')
    ->getDescription()
    ->toBe('This is a description');

it('throws an exception when the getter is called but the keyword is not set', function () {
    HasKeywordsTestSchema::make()->getDescription();
})->throws(KeywordNotSetException::class);

test('the getter method must be camel case', function () {
    HasKeywordsTestSchema::make()
        ->description('This is a description')
        ->getdescription();
})->throws(BadMethodCallException::class, 'Method "getdescription" not found');

test('throws an exception when no method is found', function ($method) {
    expect(fn () => (new HasKeywordsTestSchema)->$method())
        ->toThrow(BadMethodCallException::class, 'Method "'.$method.'" not found');
})
    ->with([
        'nonexistentMethod',
        'getnonexistentMethod',
        'NonExistentMethod',
    ]);

/**
 * Calling the private method directly appears to be the only way to properly test this.
 */
test('get methods must start with get', function () {
    $schema = new HasKeywordsTestSchema;

    $reflection = new ReflectionClass(HasKeywordsTestSchema::class);
    $method = $reflection->getMethod('keywordGetterExists');
    $method->setAccessible(true);

    $exists = $method->invoke($schema, 'theTest');
    expect($exists)->toBeFalse();
});

it('can set a keyword', function ($name) {
    $schema = HasKeywordsTestSchema::make()
        ->setKeyword($name, 'This is a description');

    expect($schema->getDescription())->toBe('This is a description');
})
    ->with([
        DescriptionKeyword::class,
        DescriptionKeyword::method(),
    ]);

it('can get a keyword', function ($name) {
    $schema = HasKeywordsTestSchema::make()
        ->description('This is a description');

    expect($schema->getKeyword($name))->toBe('This is a description');
})
    ->with([
        DescriptionKeyword::class,
        DescriptionKeyword::method(),
    ]);

it('can check if a keyword has been set', function ($name) {
    $schema = HasKeywordsTestSchema::make();

    expect($schema->hasKeyword($name))->toBeFalse();

    $schema->description('This is a description');

    expect($schema->hasKeyword($name))->toBeTrue();
})
    ->with([
        DescriptionKeyword::class,
        DescriptionKeyword::method(),
    ]);

it('can apply a keyword', function ($name) {
    $schema = HasKeywordsTestSchema::make();

    $result = collect([
        'type' => DataType::String->value,
    ]);

    $schema->theTest('This is a description');

    $result = $schema->applyKeyword($name, $result);

    expect($result)->toEqual(collect([
        'type' => DataType::String->value,
        'result' => 'I was successfully applied',
    ]));
})
    ->with([
        TheTestKeyword::class,
        TheTestKeyword::method(),
    ]);
