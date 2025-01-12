<?php

namespace BasilLangevin\LaravelDataSchemas\Transformers;

use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use Reflector;

abstract class Transformer
{
    /**
     * The reflector of the entity that the transformer is building a Schema object for.
     */
    protected ReflectionHelper $reflector;

    /**
     * The Schema object that the transformer builds.
     */
    protected Schema $schema;

    /**
     * The class name of the Schema object that the transformer builds.
     */
    protected static string $schemaClass;

    /**
     * Create a new PropertyTransformer instance.
     */
    public function __construct(Reflector|ReflectionHelper $reflector)
    {
        $this->reflector = $reflector instanceof ReflectionHelper
            ? $reflector
            : new ReflectionHelper($reflector);

        $this->schema = $this->makeSchema()->resolveKeywords($this->reflector);
    }

    /**
     * Transform a Reflector into a Schema object.
     */
    abstract public static function transform(Reflector $reflector): Schema;

    /**
     * Make a new Schema object.
     */
    protected function makeSchema(): Schema
    {
        return static::$schemaClass::make($this->reflector->getName());
    }

    /**
     * Get the Schema object.
     */
    protected function getSchema(): Schema
    {
        return $this->schema;
    }
}
