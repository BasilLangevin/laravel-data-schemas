<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers;

use BasilLangevin\LaravelDataSchemas\Types\Schema;
use Reflector;

abstract class PropertyTransformer extends Transformer
{
    /**
     * Transform a ReflectionProperty into a Schema object.
     */
    public static function transform(Reflector|ReflectionHelper $reflector): Schema
    {
        $type = $reflector->getType()->getName();

        $transformer = match (true) {
            $type === 'string' => StringTransformer::class,
            $type === 'float' => NumberTransformer::class,
            $type === 'int' => IntegerTransformer::class,
            $type === 'bool' => BooleanTransformer::class,
            enum_exists($type) => EnumTransformer::class,
        };

        return (new $transformer($reflector))->getSchema();
    }
}
