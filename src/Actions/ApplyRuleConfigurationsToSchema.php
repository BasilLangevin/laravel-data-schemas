<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresAnySchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresArraySchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresBooleanSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresIntegerSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresNumberSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresObjectSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionMethod;

class ApplyRuleConfigurationsToSchema
{
    use Runnable;

    protected static array $contracts = [
        '*' => ConfiguresAnySchema::class,
        'array' => ConfiguresArraySchema::class,
        'boolean' => ConfiguresBooleanSchema::class,
        'integer' => ConfiguresIntegerSchema::class,
        'number' => ConfiguresNumberSchema::class,
        'object' => ConfiguresObjectSchema::class,
        'string' => ConfiguresStringSchema::class,
    ];

    public function handle(Schema $schema, EntityWrapper $entity): Schema
    {
        $attributes = $this->getConfigurableAttributes($entity);

        foreach ($attributes as $attribute) {
            $this->applyConfigurations($schema, $entity, $attribute);
        }

        return $schema;
    }

    /**
     * Call each applicable configure method on the Attribute's RuleConfigurator.
     */
    protected function applyConfigurations(Schema $schema, EntityWrapper $entity, AttributeWrapper $attribute): void
    {
        $configurator = $attribute->getRuleConfigurator();

        $this->getApplicableContracts($entity)
            ->intersect(class_implements($configurator))
            ->flatMap(fn ($contract) => $this->getContractMethods($contract))
            ->unique()
            ->each(fn ($method) => $configurator::{$method}($schema, $entity, $attribute));
    }

    /**
     * Get the attributes that have a RuleConfigurator.
     */
    protected function getConfigurableAttributes(EntityWrapper $entity): Collection
    {
        return collect($entity->attributes())
            ->filter->isValidationAttribute()
            ->filter->hasRuleConfigurator();
    }

    /**
     * Get the contracts that are applicable to the entity.
     */
    protected function getApplicableContracts(EntityWrapper $entity): Collection
    {
        $contracts = collect(static::$contracts);

        if ($entity instanceof ClassWrapper) {
            return $contracts->only(['*', 'object'])->values();
        }

        return $this->getApplicablePropertyContracts($entity);
    }

    /**
     * Get the contracts that are applicable to the property.
     */
    protected function getApplicablePropertyContracts(PropertyWrapper $property): Collection
    {
        return collect(static::$contracts)
            ->filter(fn ($contract, $type) => $property->hasType($type))
            ->values();
    }

    /**
     * Get the name of the configure method defined in the contract.
     *
     * If the contract is ConfiguresIntegerSchema, also get the
     * methods defined in ConfiguresNumberSchema.
     */
    protected function getContractMethods(string $contract): Collection
    {
        $reflector = new ReflectionClass($contract);

        $methods = $reflector->getMethods(ReflectionMethod::IS_PUBLIC);

        return collect($methods)
            ->map(fn (ReflectionMethod $method) => $method->getName())
            ->when($contract === ConfiguresIntegerSchema::class, function ($methods) {
                return $methods->merge($this->getContractMethods(ConfiguresNumberSchema::class));
            });
    }
}
