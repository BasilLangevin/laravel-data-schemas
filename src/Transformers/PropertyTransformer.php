<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers;

use BasilLangevin\LaravelDataSchemas\Types\Schema;
use Reflector;

abstract class PropertyTransformer extends Transformer
{
    /**
     * Transform a ReflectionProperty into a Schema object.
     */
    public static function transform(Reflector $reflector): Schema
    {
        $type = (new ReflectionHelper($reflector))->getType()->getName();

        $transformer = match ($type) {
            'bool' => BooleanTransformer::class,
        };

        return (new $transformer($reflector))->getSchema();
    }
}
