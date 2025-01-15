<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Contracts;

use BasilLangevin\LaravelDataSchemas\Schemas\Schema;

interface ReceivesParentSchema
{
    public function parentSchema(Schema $parentSchema): self;
}
