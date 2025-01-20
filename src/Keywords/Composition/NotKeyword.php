<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Composition;

use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\MergesMultipleInstancesIntoAllOf;
use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\ReceivesParentSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Schema;
use Closure;
use Illuminate\Support\Collection;

class NotKeyword extends Keyword implements MergesMultipleInstancesIntoAllOf, ReceivesParentSchema
{
    protected Schema $parentSchema;

    public function __construct(protected Closure $callback) {}

    public function parentSchema(Schema $parentSchema): self
    {
        $this->parentSchema = $parentSchema;

        return $this;
    }

    /**
     * Get the value of the keyword.
     */
    public function get(): Closure
    {
        return $this->callback;
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        $notSchema = $this->parentSchema->cloneBaseStructure();

        ($this->callback)($notSchema);

        return $schema->merge(['not' => $notSchema->toArray()]);
    }
}
