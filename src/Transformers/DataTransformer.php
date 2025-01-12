<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers;

use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use ReflectionProperty;
use Reflector;

class DataTransformer extends Transformer
{
    public static string $schemaClass = ObjectSchema::class;

    /**
     * Transform a ReflectionProperty into a Schema object.
     */
    public static function transform(Reflector $reflector): Schema
    {
        return (new self($reflector))->getSchema();
    }

    /**
     * Make a new Schema object.
     */
    protected function makeSchema(): Schema
    {
        return static::$schemaClass::make($this->reflector->getShortName());
    }
}
