<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers;

use BasilLangevin\LaravelDataSchemas\Types\IntegerSchema;
use BasilLangevin\LaravelDataSchemas\Types\Schema;
use BasilLangevin\LaravelDataSchemas\Types\StringSchema;

class EnumTransformer extends PropertyTransformer
{
    /**
     * Make a new Schema object based on the enum backing type.
     */
    protected function makeSchema(): Schema
    {
        $enum = $this->reflector->getType()->getName();
        $enumReflection = new \ReflectionEnum($enum);
        $enumBackingType = $enumReflection->getBackingType()->getName();

        $schemaClass = match ($enumBackingType) {
            'string' => StringSchema::class,
            'int' => IntegerSchema::class,
        };

        return $schemaClass::make($this->reflector->getName())
            ->enum($enum);
    }
}
