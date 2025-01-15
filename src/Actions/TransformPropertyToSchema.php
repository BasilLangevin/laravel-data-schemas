<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class TransformPropertyToSchema
{
    use Runnable;

    public function handle(PropertyWrapper $property)
    {
        $schemaClass = $this->getSchemaClass($property);

        return $schemaClass::make($property->getName())
            ->when($property->isEnum(), fn (Schema $schema) => ApplyEnumToSchema::run($schema, $property))
            ->pipe(fn (Schema $schema) => ApplyAnnotationsToSchema::run($schema, $property))
            ->pipe(fn (Schema $schema) => ApplyRuleConfigurationsToSchema::run($schema, $property));
    }

    protected function getSchemaClass(PropertyWrapper $property): string
    {
        $type = $property->getType()->getName();

        return match (true) {
            $type === 'string' => StringSchema::class,
            $type === 'float' => NumberSchema::class,
            $type === 'int' => IntegerSchema::class,
            $type === 'bool' => BooleanSchema::class,
            $type === 'array' => ArraySchema::class,
            $type === 'object' => ObjectSchema::class,
            enum_exists($type) => $this->getEnumSchemaClass($type),
        };
    }

    protected function getEnumSchemaClass(string $enum): string
    {
        $reflector = new \ReflectionEnum($enum);
        $type = $reflector->getBackingType()->getName();

        return match ($type) {
            'string' => StringSchema::class,
            'int' => IntegerSchema::class,
        };
    }
}
