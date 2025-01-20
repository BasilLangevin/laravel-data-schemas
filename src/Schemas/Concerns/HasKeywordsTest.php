<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Exceptions\KeywordNotSetException;
use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Composition\NotKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\MergesMultipleInstancesIntoAllOf;
use BasilLangevin\LaravelDataSchemas\Keywords\General\TypeKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Concerns\HasKeywords;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
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
}

class TheHandlesMultipleInstancesTestKeyword extends TheTestKeyword implements HandlesMultipleInstances
{
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        return $schema->merge([
            'result' => 'I was successfully applied multiple times',
        ]);
    }
}

class TheMergesMultipleInstancesIntoAllOfTestKeyword extends Keyword implements MergesMultipleInstancesIntoAllOf
{
    public function __construct(protected mixed $value) {}

    public function get(): mixed
    {
        return $this->value;
    }

    public function apply(Collection $schema): Collection
    {
        return $schema->merge([
            'result' => $this->get(),
        ]);
    }
}

class HasKeywordsTestSchema extends StringSchema
{
    public static array $keywords = [
        TypeKeyword::class,
        DescriptionKeyword::class,
        TheTestKeyword::class,
        TheHandlesMultipleInstancesTestKeyword::class,
        TheMergesMultipleInstancesIntoAllOfTestKeyword::class,
    ];
}

it('can call a keyword method')
    ->expect(HasKeywordsTestSchema::make())
    ->description('This is a description')
    ->getDescription()
    ->toBe('This is a description');

test('calling a keyword method multiple times adds multiple instances of the keyword')
    ->expect(HasKeywordsTestSchema::make())
    ->description('This is a description')
    ->description('This is a new description')
    ->getDescription()->toArray()
    ->toBe([
        'This is a description',
        'This is a new description',
    ]);

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

it('passes itself to any schema that implements the ReceivesParentSchema interface', function () {
    $parentSchema = StringSchema::make();

    $parentSchema->not(fn (StringSchema $schema) => null);

    $reflectionObject = new ReflectionObject($parentSchema);
    $reflectionObject->getMethod('getKeywordInstances')->setAccessible(true);
    $notInstances = $reflectionObject->getMethod('getKeywordInstances')->invoke($parentSchema, NotKeyword::class);

    $not = $notInstances[0];

    $reflectionProperty = new ReflectionProperty(NotKeyword::class, 'parentSchema');
    $reflectionProperty->setAccessible(true);

    expect($reflectionProperty->getValue($not))->toBe($parentSchema);
});

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

it('calls the apply method of the keyword when only one instance is set', function () {
    $schema = HasKeywordsTestSchema::make();

    $schema->theHandlesMultipleInstancesTest('This is a description');

    $result = $schema->applyKeyword(TheHandlesMultipleInstancesTestKeyword::class, collect([
        'type' => DataType::String->value,
    ]));

    expect($result)->toEqual(collect([
        'type' => DataType::String->value,
        'result' => 'I was successfully applied',
    ]));
});

it('applies the last instance of a keyword when multiple instances are set and the keyword does not implement HandlesMultipleInstances', function () {
    $schema = HasKeywordsTestSchema::make();

    $schema->theTest('This is a description');
    $schema->theTest('This is a new description');

    $result = $schema->applyKeyword(TheTestKeyword::class, collect([
        'type' => DataType::String->value,
    ]));

    expect($result)->toEqual(collect([
        'type' => DataType::String->value,
        'result' => 'I was successfully applied',
    ]));
});

it('can apply multiple instances of a keyword when the keyword implements HandlesMultipleInstances', function () {
    $schema = HasKeywordsTestSchema::make();

    $schema->theHandlesMultipleInstancesTest('This is a description');
    $schema->theHandlesMultipleInstancesTest('This is a new description');

    $result = $schema->applyKeyword(TheHandlesMultipleInstancesTestKeyword::class, collect([
        'type' => DataType::String->value,
    ]));

    expect($result)->toEqual(collect([
        'type' => DataType::String->value,
        'result' => 'I was successfully applied multiple times',
    ]));
});

it('combines multiple instances of a keyword into an allOf when the keyword implements MergesMultipleInstancesIntoAllOf')
    ->expect(HasKeywordsTestSchema::make()->theMergesMultipleInstancesIntoAllOfTest('This is a description')->theMergesMultipleInstancesIntoAllOfTest('This is a new description'))
    ->applyKeyword(TheMergesMultipleInstancesIntoAllOfTestKeyword::class, collect([
        'type' => DataType::String->value,
    ]))
    ->toEqual(collect([
        'type' => DataType::String->value,
        'allOf' => [
            ['result' => 'This is a description'],
            ['result' => 'This is a new description'],
        ],
    ]));

it('ignores duplicate instances of a keyword when merging into an allOf')
    ->expect(HasKeywordsTestSchema::make()->theMergesMultipleInstancesIntoAllOfTest('This is a description')->theMergesMultipleInstancesIntoAllOfTest('This is a description')->theMergesMultipleInstancesIntoAllOfTest('This is a new description'))
    ->applyKeyword(TheMergesMultipleInstancesIntoAllOfTestKeyword::class, collect([
        'type' => DataType::String->value,
    ]))
    ->toEqual(collect([
        'type' => DataType::String->value,
        'allOf' => [
            ['result' => 'This is a description'],
            ['result' => 'This is a new description'],
        ],
    ]));

test('when all instances of an keyword that implements MergesMultipleInstancesIntoAllOf are the same it applies the keyword to the schema without wrapping it in an allOf')
    ->expect(HasKeywordsTestSchema::make()->theMergesMultipleInstancesIntoAllOfTest('This is a description')->theMergesMultipleInstancesIntoAllOfTest('This is a description'))
    ->applyKeyword(TheMergesMultipleInstancesIntoAllOfTestKeyword::class, collect([
        'type' => DataType::String->value,
    ]))
    ->toEqual(collect([
        'type' => DataType::String->value,
        'result' => 'This is a description',
    ]));

it('merges multiple MergesMultipleInstancesIntoAllOf keywords into a single allOf', function () {
    $schema = StringSchema::make();

    $schema->not(fn (StringSchema $schema) => $schema->minLength(42));
    $schema->not(fn (StringSchema $schema) => $schema->minLength(43));

    $schema->pattern('/^[a-z]+$/');
    $schema->pattern('/^[0-9]+$/');

    expect($schema->toArray())->toEqual([
        'allOf' => [
            ['not' => ['minLength' => 42]],
            ['not' => ['minLength' => 43]],
            ['pattern' => '/^[a-z]+$/'],
            ['pattern' => '/^[0-9]+$/'],
        ],
    ]);
});
