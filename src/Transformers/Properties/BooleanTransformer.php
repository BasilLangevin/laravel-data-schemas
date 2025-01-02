<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers\Properties;

use BasilLangevin\LaravelDataSchemas\Types\BooleanSchema;
use BasilLangevin\LaravelDataSchemas\Types\Schema;
use ReflectionProperty;

class BooleanTransformer extends PropertyTransformer
{
    protected static string $schemaClass = BooleanSchema::class;

    /**
     * Transform a ReflectionProperty into a BooleanSchema object.
     */
    public static function transform(ReflectionProperty $property): Schema
    {
        return (new self($property))
            ->addDescription()
            ->getSchema();
    }
}
