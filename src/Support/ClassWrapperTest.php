<?php

use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

covers(ClassWrapper::class);

#[Title('Test'), CustomAnnotation('test1', 'value1'), CustomAnnotation('test2', 'value2')]
class TestClassWrapperClass
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

it('can get its name')
    ->expect(ClassWrapper::make(TestClassWrapperClass::class)->getName())
    ->toBe('TestClassWrapperClass');

it('can check if it has an attribute', function () {
    $reflector = ClassWrapper::make(TestClassWrapperClass::class);

    expect($reflector->hasAttribute(Title::class))->toBe(true);
});

it('can get an attribute', function () {
    $reflector = ClassWrapper::make(TestClassWrapperClass::class);

    expect($reflector->getAttribute(Title::class))
        ->toBeInstanceOf(AttributeWrapper::class)
        ->getValue()->toBe('Test');
});

it('can get multiple attributes of the same type', function () {
    $reflector = ClassWrapper::make(TestClassWrapperClass::class);

    expect($reflector->getAttribute(CustomAnnotation::class))
        ->toBeCollection()
        ->toHaveCount(2)
        ->each->toBeInstanceOf(AttributeWrapper::class);
});

it('can get its properties', function () {
    $reflector = ClassWrapper::make(TestClassWrapperClass::class);

    expect($reflector->properties())
        ->toBeCollection()
        ->toHaveCount(3)
        ->each->toBeInstanceOf(PropertyWrapper::class);

    expect($reflector->properties()->first()->getName())
        ->toBe('test');
});
