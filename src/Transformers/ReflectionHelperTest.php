<?php

use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use ReflectionClass;

#[Title('Test'), CustomAnnotation('test1', 'value1'), CustomAnnotation('test2', 'value2')]
class TestReflectionHelperClass
{
    public string $test;

    public int $testInt;

    public bool $testBoolean;

    protected string $hidden;

    public function test()
    {
        return 'test';
    }
}

it('can check if the reflected entity is a class', function () {
    $reflector = new ReflectionHelper(new ReflectionClass(TestReflectionHelperClass::class));

    expect($reflector->isClass())->toBe(true);
});

test('isClass returns false if the reflected entity is not a class', function () {
    $reflector = new ReflectionHelper(new ReflectionProperty(TestReflectionHelperClass::class, 'test'));

    expect($reflector->isClass())->toBe(false);
});

it('can check if the reflected entity is a property', function () {
    $property = new ReflectionProperty(TestReflectionHelperClass::class, 'test');

    $reflector = new ReflectionHelper($property);

    expect($reflector->isProperty())->toBe(true);
});

test('isProperty returns false if the reflected entity is not a property', function () {
    $reflector = new ReflectionHelper(new ReflectionClass(TestReflectionHelperClass::class));

    expect($reflector->isProperty())->toBe(false);
});

it('can check if the reflected entity is a string', function () {
    $reflector = new ReflectionHelper(new ReflectionProperty(TestReflectionHelperClass::class, 'test'));

    expect($reflector->isString())->toBe(true);
});

test('isString returns false if the reflected entity is not a string', function () {
    $reflector = new ReflectionHelper(new ReflectionProperty(TestReflectionHelperClass::class, 'testInt'));

    expect($reflector->isString())->toBe(false);
});

test('isString returns false if the reflected entity is not a property', function () {
    $reflector = new ReflectionHelper(new ReflectionClass(TestReflectionHelperClass::class));

    expect($reflector->isString())->toBe(false);
});

it('can check if the reflected entity is an integer', function () {
    $reflector = new ReflectionHelper(new ReflectionProperty(TestReflectionHelperClass::class, 'testInt'));

    expect($reflector->isInteger())->toBe(true);
});

test('isInteger returns false if the reflected entity is not an integer', function () {
    $reflector = new ReflectionHelper(new ReflectionProperty(TestReflectionHelperClass::class, 'test'));

    expect($reflector->isInteger())->toBe(false);
});

test('isInteger returns false if the reflected entity is not a property', function () {
    $reflector = new ReflectionHelper(new ReflectionClass(TestReflectionHelperClass::class));

    expect($reflector->isInteger())->toBe(false);
});

it('can check if the reflected entity is a boolean', function () {
    $reflector = new ReflectionHelper(new ReflectionProperty(TestReflectionHelperClass::class, 'testBoolean'));

    expect($reflector->isBoolean())->toBe(true);
});

test('isBoolean returns false if the reflected entity is not a boolean', function () {
    $reflector = new ReflectionHelper(new ReflectionProperty(TestReflectionHelperClass::class, 'test'));

    expect($reflector->isBoolean())->toBe(false);
});

test('isBoolean returns false if the reflected entity is not a property', function () {
    $reflector = new ReflectionHelper(new ReflectionClass(TestReflectionHelperClass::class));

    expect($reflector->isBoolean())->toBe(false);
});

it('can call a method on the reflector', function () {
    $reflector = new ReflectionHelper(new ReflectionClass(TestReflectionHelperClass::class));
    $reflector->isCloneable();
})->throwsNoExceptions();

it('cannot call a method on the reflector that does not exist', function () {
    $reflector = new ReflectionHelper(new ReflectionClass(TestReflectionHelperClass::class));
    $reflector->doesNotExist();
})->throws(BadMethodCallException::class);

it('can get its reflector class name', function () {
    $reflector = new ReflectionHelper(new ReflectionClass(TestReflectionHelperClass::class));

    expect($reflector->getReflectorClassName())
        ->toBe(ReflectionClass::class);
});

it('can check if it has an attribute', function () {
    $reflector = new ReflectionHelper(new ReflectionClass(TestReflectionHelperClass::class));

    expect($reflector->hasAttribute(Title::class))->toBe(true);
});

it('can get an attribute', function () {
    $reflector = new ReflectionHelper(new ReflectionClass(TestReflectionHelperClass::class));

    expect($reflector->getAttribute(Title::class))
        ->toBeInstanceOf(Title::class)
        ->getTitle()->toBe('Test');
});

it('can get multiple attributes of the same type', function () {
    $reflector = new ReflectionHelper(new ReflectionClass(TestReflectionHelperClass::class));

    expect($reflector->getAttribute(CustomAnnotation::class))
        ->toBeCollection()
        ->toHaveCount(2)
        ->each->toBeInstanceOf(CustomAnnotation::class);
});

it('can get the properties', function () {
    $reflector = new ReflectionHelper(new ReflectionClass(TestReflectionHelperClass::class));

    expect($reflector->properties())
        ->toBeCollection()
        ->toHaveCount(3)
        ->each->toBeInstanceOf(ReflectionHelper::class);

    expect($reflector->properties()->first())
        ->name->toBe('test');
});

it('can get its declaring class', function () {
    $reflector = new ReflectionHelper(new ReflectionProperty(TestReflectionHelperClass::class, 'test'));

    expect($reflector->getClass())->toBeInstanceOf(ReflectionHelper::class);
    expect($reflector->getClass()->getName())->toBe(TestReflectionHelperClass::class);
});

it('cannot get its declaring class if it is not a property', function () {
    $reflector = new ReflectionHelper(new ReflectionClass(TestReflectionHelperClass::class));

    $reflector->getClass();
})->throws(\Exception::class, 'The reflector is not a property.');

it('can get its siblings', function () {
    $reflector = new ReflectionHelper(new ReflectionProperty(TestReflectionHelperClass::class, 'test'));

    expect($reflector->siblings())->toBeCollection()
        ->toHaveCount(2)
        ->each->toBeInstanceOf(ReflectionHelper::class);

    expect($reflector->siblings()->map->getName()->toArray())
        ->toBe(['testInt', 'testBoolean']);
});

it('can get its sibling names', function () {
    $reflector = new ReflectionHelper(new ReflectionProperty(TestReflectionHelperClass::class, 'test'));

    expect($reflector->siblingNames())->toBeCollection();
    expect($reflector->siblingNames()->toArray())->toBe(['testInt', 'testBoolean']);
});
