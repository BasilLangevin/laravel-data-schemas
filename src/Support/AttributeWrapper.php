<?php

namespace BasilLangevin\LaravelDataSchemas\Support;

use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataSchemas\Attributes\Description;
use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use Spatie\LaravelData\Attributes\Validation\StringValidationAttribute;
use Spatie\LaravelData\Attributes\Validation\ValidationAttribute;

class AttributeWrapper
{
    protected object $instance;

    public function __construct(protected \ReflectionAttribute $attribute)
    {
        $this->instance = $attribute->newInstance();
    }

    /**
     * Get the name of the attribute.
     */
    public function getName(): string
    {
        return $this->attribute->getName();
    }

    /**
     * Get the value of the attribute.
     */
    public function getValue(): mixed
    {
        return match (true) {
            $this->instance instanceof StringValidationAttribute => $this->instance->parameters()[0],
            $this->instance instanceof Title => $this->instance->getTitle(),
            $this->instance instanceof Description => $this->instance->getDescription(),
            $this->instance instanceof CustomAnnotation => $this->instance->getCustomAnnotation(),
            default => throw new \Exception('Attribute value not supported'),
        };
    }

    /**
     * Check if the attribute is a Spatie/laravel-data validation attribute.
     */
    public function isValidationAttribute(): bool
    {
        return $this->instance instanceof ValidationAttribute;
    }

    protected function getRuleConfiguratorClassName(): string
    {
        $namespace = 'BasilLangevin\LaravelDataSchemas\RuleConfigurators';

        return $namespace.'\\'.class_basename($this->getName()).'RuleConfigurator';
    }

    /**
     * Check if the attribute has a rule configurator.
     */
    public function hasRuleConfigurator(): bool
    {
        return class_exists($this->getRuleConfiguratorClassName());
    }

    /**
     * Get the rule configurator class name.
     */
    public function getRuleConfigurator(): ?string
    {
        if (! $this->hasRuleConfigurator()) {
            return null;
        }

        return $this->getRuleConfiguratorClassName();
    }
}
