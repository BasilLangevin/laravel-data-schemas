<?php

use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\AlphaRuleConfigurator;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Argument;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Enums\TestStringEnum;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\Alpha;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\NotIn;

covers(AttributeWrapper::class);

uses(TestsSchemaTransformation::class);

it('can get the name of the attribute', function () {
    $this->class->addStringProperty('test', [Title::class => 'test']);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(Title::class);

    expect($attribute->getName())->toBe(Title::class);
});

it('can get the value of a StringAttribute', function () {
    $this->class->addStringProperty('test', [Title::class => 'test']);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(Title::class);

    expect($attribute->getValue())->toBe('test');
});

it('can get the value of an ArrayAttribute', function () {
    $this->class->addStringProperty('test', [CustomAnnotation::class => ['test', 'test annotation']]);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(CustomAnnotation::class);

    expect($attribute->getValue())->toBe(['test' => 'test annotation']);
});

it('can get the value of a CustomAnnotation attribute set with an array', function () {
    $this->class->addStringProperty('test', [CustomAnnotation::class => new Argument("['test' => 'test annotation']")]);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(CustomAnnotation::class);

    expect($attribute->getValue())->toBe(['test' => 'test annotation']);
});

it('can get the value of a StringValidationAttribute', function () {
    $this->class->addStringProperty('test', [After::class => '2025-01-01']);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(After::class);

    expect($attribute->getValue())->toBe('2025-01-01');
});

it('can get the value of a Enum attribute', function () {
    $this->class->addStringProperty('test', [Enum::class => TestStringEnum::class]);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(Enum::class);

    expect($attribute->getValue())->toBe(TestStringEnum::class);
});

it('can get the value of a In attribute', function () {
    $this->class->addStringProperty('test', [In::class => ['one', 'two']]);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(In::class);

    expect($attribute->getValue())->toBe(['one', 'two']);
});

it('can get the value of a NotIn attribute', function () {
    $this->class->addStringProperty('test', [NotIn::class => ['one', 'two']]);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(NotIn::class);

    expect($attribute->getValue())->toBe(['one', 'two']);
});

#[Attribute]
class TestAttribute
{
    public function __construct(public string $value) {}
}

it('throws an exception when getting the value of an unsupported attribute', function () {
    $this->class->addStringProperty('test', [TestAttribute::class => 'test']);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(TestAttribute::class);

    $attribute->getValue();
})->throws(\Exception::class, 'Attribute value not supported');

test('getValue returns null when the attribute has no parameters', function () {
    $this->class->addStringProperty('test', [Alpha::class]);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(Alpha::class);

    expect($attribute->getValue())->toBeNull();
});

it('can check if its a validation attribute', function () {
    $this->class->addStringProperty('test', [Alpha::class]);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(Alpha::class);

    expect($attribute->isValidationAttribute())->toBeTrue();
});

it('can check if its not a validation attribute', function () {
    $this->class->addStringProperty('test', [Title::class => 'test']);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(Title::class);

    expect($attribute->isValidationAttribute())->toBeFalse();
});

it('can check if it has a rule configurator', function () {
    $this->class->addStringProperty('test', [Alpha::class]);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(Alpha::class);

    expect($attribute->hasRuleConfigurator())->toBeTrue();
});

it('can check if it does not have a rule configurator', function () {
    $this->class->addStringProperty('test', [Title::class => 'test']);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(Title::class);

    expect($attribute->hasRuleConfigurator())->toBeFalse();
});

it('can get the rule configurator class name', function () {
    $this->class->addStringProperty('test', [Alpha::class]);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(Alpha::class);

    expect($attribute->getRuleConfigurator())->toBe(AlphaRuleConfigurator::class);
});

test('getRuleConfigurator returns null when the attribute has no rule configurator', function () {
    $this->class->addStringProperty('test', [Title::class => 'test']);
    $property = $this->class->getClassProperty('test');
    $attribute = $property->getAttribute(Title::class);

    expect($attribute->getRuleConfigurator())->toBeNull();
});
