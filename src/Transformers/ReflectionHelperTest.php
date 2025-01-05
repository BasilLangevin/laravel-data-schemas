<?php

use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\Transformers\ReflectionHelper;
use ReflectionClass;

#[Title('Test')]
class TestReflectionHelperClass
{
    public string $test;

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

it('can get the properties', function () {
    $reflector = new ReflectionHelper(new ReflectionClass(TestReflectionHelperClass::class));

    expect($reflector->properties())
        ->toBeCollection()
        ->toHaveCount(1)
        ->each->toBeInstanceOf(ReflectionHelper::class);

    expect($reflector->properties()->first())
        ->name->toBe('test');
});
