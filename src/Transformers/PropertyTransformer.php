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

        $transformer = match ($type) {
            'bool' => BooleanTransformer::class,
        };

        return (new $transformer($reflector))->getSchema();
    }
}
