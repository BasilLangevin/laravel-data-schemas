<?php

namespace BasilLangevin\LaravelDataSchemas\Tests;

use BasilLangevin\LaravelDataSchemas\Tests\Support\DataClassBuilder;

trait TestsSchemaTransformation
{
    public function setupTestsSchemaTransformation(): void
    {
        $this->class = new DataClassBuilder;

        expect()->extend('toHaveSchema', function (string|array $property, ?array $schema = null) {
            if (is_array($property)) {
                $schema = $property;
                $property = null;
            }

            return $this->getSchema($property)->toEqual($schema);
        });
    }
}
