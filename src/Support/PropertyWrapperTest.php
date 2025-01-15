<?php

use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Enums\TestStringEnum;

covers(PropertyWrapper::class);

class TestPropertyWrapperClass
{
    #[Title('Test'), CustomAnnotation('test1', 'value1'), CustomAnnotation('test2', 'value2')]
    public string $test;

    public array $testArray;

    public bool $testBoolean;

    public float $testFloat;

    public int $testInt;

    public TestStringEnum $testEnum;

    protected string $hidden;

    public function test()
    {
        return 'test';
    }
}

it('can check if the reflected property is an array', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'testArray');

    expect($reflector->isArray())->toBe(true);
});

test('isArray returns false if the reflected property is not an array', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'test');

    expect($reflector->isArray())->toBe(false);
});

it('can check if the reflected property is a boolean', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'testBoolean');

    expect($reflector->isBoolean())->toBe(true);
});

test('isBoolean returns false if the reflected property is not a boolean', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'test');

    expect($reflector->isBoolean())->toBe(false);
});

it('can check if the reflected property is an enum', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'testEnum');

    expect($reflector->isEnum())->toBe(true);
});

test('isEnum returns false if the reflected property is not an enum', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'test');

    expect($reflector->isEnum())->toBe(false);
});

it('can check if the reflected property is a float', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'testFloat');

    expect($reflector->isFloat())->toBe(true);
});

test('isFloat returns false if the reflected property is not a float', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'test');

    expect($reflector->isFloat())->toBe(false);
});

it('can check if the reflected property is an integer', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'testInt');

    expect($reflector->isInteger())->toBe(true);
});

test('isInteger returns false if the reflected property is not an integer', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'test');

    expect($reflector->isInteger())->toBe(false);
});

it('can check if the reflected property is a string', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'test');

    expect($reflector->isString())->toBe(true);
});

test('isString returns false if the reflected property is not a string', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'testInt');

    expect($reflector->isString())->toBe(false);
});

it('can check if it has an attribute', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'test');

    expect($reflector->hasAttribute(Title::class))->toBe(true);
});

it('can get an attribute', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'test');

    expect($reflector->getAttribute(Title::class))
        ->toBeInstanceOf(AttributeWrapper::class)
        ->getName()->toBe(Title::class)
        ->getValue()->toBe('Test');
});

it('can get multiple attributes of the same type', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'test');

    expect($reflector->attributes(CustomAnnotation::class))
        ->toBeCollection()
        ->toHaveCount(2)
        ->each->toBeInstanceOf(AttributeWrapper::class);

    expect($reflector->attributes(CustomAnnotation::class)->map->getName()->toArray())
        ->toBe([CustomAnnotation::class, CustomAnnotation::class]);
});

it('can get its declaring class', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'test');

    expect($reflector->getClass())->toBeInstanceOf(ClassWrapper::class);
    expect($reflector->getClass()->getName())->toBe(TestPropertyWrapperClass::class);
});

it('can get its siblings', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'test');

    expect($reflector->siblings())->toBeCollection()
        ->toHaveCount(5)
        ->each->toBeInstanceOf(PropertyWrapper::class);

    expect($reflector->siblings()->map->getName()->toArray())
        ->toBe(['testArray', 'testBoolean', 'testFloat', 'testInt', 'testEnum']);
});

it('can get its sibling names', function () {
    $reflector = PropertyWrapper::make(TestPropertyWrapperClass::class, 'test');

    expect($reflector->siblingNames())->toBeCollection();
    expect($reflector->siblingNames()->toArray())->toBe(['testArray', 'testBoolean', 'testFloat', 'testInt', 'testEnum']);
});
