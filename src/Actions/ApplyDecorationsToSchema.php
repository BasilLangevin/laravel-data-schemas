<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Decorators\CustomAnnotationDecorator;
use BasilLangevin\LaravelDataSchemas\Decorators\DescriptionDecorator;
use BasilLangevin\LaravelDataSchemas\Decorators\TitleDecorator;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;

class ApplyDecorationsToSchema
{
    use Runnable;

    protected static array $decorators = [
        TitleDecorator::class,
        DescriptionDecorator::class,
        CustomAnnotationDecorator::class,
    ];

    public function handle(Schema $schema, EntityWrapper $entity): Schema
    {
        foreach (self::$decorators as $decorator) {
            $decorator::decorateSchema($schema, $entity);
        }

        return $schema;
    }
}
