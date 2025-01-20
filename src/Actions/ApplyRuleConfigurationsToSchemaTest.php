<?php

use BasilLangevin\LaravelDataSchemas\Actions\ApplyRuleConfigurationsToSchema;
use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresAnySchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresArraySchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresBooleanSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresIntegerSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresNumberSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresObjectSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Alpha;
use Spatie\LaravelData\Attributes\Validation\ValidationAttribute;

covers(ApplyRuleConfigurationsToSchema::class);

uses(TestsSchemaTransformation::class);

it('applies the correct rule configurations to the schema', function () {
    $this->class->addStringProperty('name', [Alpha::class, Title::class => 'title']);

    $property = $this->class->getClassProperty('name');

    $action = new ApplyRuleConfigurationsToSchema;

    $schema = new StringSchema;

    $result = $action->handle($schema, $property);

    expect($result->getPattern())->toBe('/^[a-zA-Z]+$/');

    expect(fn () => $result->getTitle())->toThrow(Exception::class, 'The keyword "title" has not been set.');
});

test('getConfigurableAttributes only includes validation attributes', function () {
    $this->class->addStringProperty('name', [Alpha::class, Title::class => 'title']);
    $property = $this->class->getClassProperty('name');

    $action = new ApplyRuleConfigurationsToSchema;

    $reflection = new ReflectionClass($action);
    $method = $reflection->getMethod('getConfigurableAttributes');
    $method->setAccessible(true);

    $result = $method->invokeArgs($action, [$property]);

    expect($result)->toBeCollection()->toHaveCount(1);
    expect($result->map->getName()->all())->toBe([Alpha::class]);
});

test('getConfigurableAttributes only includes attributes with a rule configurator', function () {
    #[Attribute]
    class AttributeWithoutRuleConfigurator extends ValidationAttribute
    {
        public static function keyword(): string
        {
            return 'alpha';
        }

        public static function create(string ...$parameters): static
        {
            return new static;
        }
    }

    $this->class->addStringProperty('name', [Alpha::class, AttributeWithoutRuleConfigurator::class]);

    $property = $this->class->getClassProperty('name');

    $action = new ApplyRuleConfigurationsToSchema;

    $reflection = new ReflectionClass($action);
    $method = $reflection->getMethod('getConfigurableAttributes');
    $method->setAccessible(true);

    $result = $method->invokeArgs($action, [$property]);

    expect($result)->toBeCollection()->toHaveCount(1);
    expect($result->map->getName()->all())->toBe([Alpha::class]);
});

test('getApplicableContracts returns the correct contracts', function ($type, $contracts) {
    $this->class->addProperty($type, 'name');

    $property = $this->class->getClassProperty('name');
    $action = new ApplyRuleConfigurationsToSchema;

    $reflection = new ReflectionClass($action);
    $method = $reflection->getMethod('getApplicableContracts');
    $method->setAccessible(true);

    $result = $method->invokeArgs($action, [$property]);

    expect($result->all())->toBe($contracts);
})->with([
    ['array', [ConfiguresAnySchema::class, ConfiguresArraySchema::class]],
    ['bool', [ConfiguresAnySchema::class, ConfiguresBooleanSchema::class]],
    ['int', [ConfiguresAnySchema::class, ConfiguresIntegerSchema::class, ConfiguresNumberSchema::class]],
    ['float', [ConfiguresAnySchema::class, ConfiguresNumberSchema::class]],
    ['object', [ConfiguresAnySchema::class, ConfiguresObjectSchema::class]],
    ['string', [ConfiguresAnySchema::class, ConfiguresStringSchema::class]],
]);

it('returns the correct contracts for a class wrapper', function () {
    $this->class->addStringProperty('name');

    $property = $this->class->getClassProperty('name');
    $wrapper = $property->getClass();
    $action = new ApplyRuleConfigurationsToSchema;

    $reflection = new ReflectionClass($action);
    $method = $reflection->getMethod('getApplicableContracts');
    $method->setAccessible(true);

    $result = $method->invokeArgs($action, [$wrapper]);

    expect($result)->toBeCollection()->toHaveCount(2);
    expect($result->all())->toBe([ConfiguresAnySchema::class, ConfiguresObjectSchema::class]);
});

it('gets the correct methods for a contract', function ($contract, $methods) {
    $action = new ApplyRuleConfigurationsToSchema;

    $reflection = new ReflectionClass($action);
    $method = $reflection->getMethod('getContractMethods');
    $method->setAccessible(true);

    $result = $method->invokeArgs($action, [$contract]);

    expect($result->all())->toBe($methods);
})->with([
    [ConfiguresAnySchema::class, ['configureSchema']],
    [ConfiguresArraySchema::class, ['configureArraySchema']],
    [ConfiguresBooleanSchema::class, ['configureBooleanSchema']],
    [ConfiguresIntegerSchema::class, ['configureIntegerSchema', 'configureNumberSchema']],
    [ConfiguresNumberSchema::class, ['configureNumberSchema']],
    [ConfiguresObjectSchema::class, ['configureObjectSchema']],
    [ConfiguresStringSchema::class, ['configureStringSchema']],
]);
