<?php

use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;

covers(AttributeWrapper::class);

#[Title('test')]
class TestAttributeWrapperClass {}

beforeEach(function () {
    $this->class = ClassWrapper::make(TestAttributeWrapperClass::class);
    $this->attribute = $this->class->getAttribute(Title::class);
});

it('can get the name of the attribute', function () {
    expect($this->attribute->getName())->toBe(Title::class);
});

it('can get the value of the attribute', function () {
    expect($this->attribute->getValue())->toBe('test');
});
